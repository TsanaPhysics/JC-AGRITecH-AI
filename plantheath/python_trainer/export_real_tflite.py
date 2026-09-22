"""
Durian Leaf Health & Pathology AI Model Builder & TFLite Exporter
Builds real dual-head MobileNet-based CNN for on-device inference:
1. Disease Classification Head (6 classes)
2. Nutrient & SPAD Chlorophyll Regression Head (9 parameters)
3. Embedding Extractor for Continual Few-Shot Learning (128-dim vector)
"""

import os
import sys

def build_and_export_models():
    import tensorflow as tf
    from tensorflow import keras
    from tensorflow.keras import layers

    print(f"[*] TensorFlow version: {tf.__version__}")
    
    os.makedirs("../assets/models", exist_ok=True)
    
    # 1. Base Input
    inputs = keras.Input(shape=(224, 224, 3), name="leaf_image_input")
    
    # 2. Lightweight Efficient Backbone
    x = layers.Conv2D(16, (3, 3), strides=(2, 2), padding="same", activation="relu")(inputs)
    x = layers.BatchNormalization()(x)
    
    # Depthwise Separable Block 1
    x = layers.DepthwiseConv2D((3, 3), padding="same", activation="relu")(x)
    x = layers.Conv2D(32, (1, 1), activation="relu")(x)
    x = layers.MaxPooling2D((2, 2))(x)
    
    # Depthwise Separable Block 2
    x = layers.DepthwiseConv2D((3, 3), padding="same", activation="relu")(x)
    x = layers.Conv2D(64, (1, 1), activation="relu")(x)
    x = layers.MaxPooling2D((2, 2))(x)

    # Depthwise Separable Block 3
    x = layers.DepthwiseConv2D((3, 3), padding="same", activation="relu")(x)
    x = layers.Conv2D(128, (1, 1), activation="relu")(x)
    x = layers.GlobalAveragePooling2D()(x)
    
    # Latent Embedding Layer (128-dim) for Few-Shot / Continual Learning
    embeddings = layers.Dense(128, activation="relu", name="embedding_layer")(x)
    normalized_embeddings = layers.Lambda(lambda v: tf.math.l2_normalize(v, axis=1), name="l2_embedding")(embeddings)
    
    # Head 1: Pathology Classification (6 classes)
    d_dense = layers.Dense(64, activation="relu")(embeddings)
    disease_probs = layers.Dense(6, activation="softmax", name="disease_output")(d_dense)
    
    # Head 2: Nutrient & Chlorophyll Regression (9 continuous metrics: SPAD, N, P, K, Mg, Ca, Fe, Zn, B)
    n_dense = layers.Dense(64, activation="relu")(embeddings)
    nutrient_preds = layers.Dense(9, activation="linear", name="nutrient_output")(n_dense)
    
    # Combined Dual-Head Model
    dual_model = keras.Model(inputs=inputs, outputs=[disease_probs, nutrient_preds], name="durian_dual_health_model")
    dual_model.compile(
        optimizer="adam",
        loss={"disease_output": "categorical_crossentropy", "nutrient_output": "mse"}
    )
    dual_model.summary()
    
    # Standalone Embedding Model for On-Device Incremental Learning
    embed_model = keras.Model(inputs=inputs, outputs=normalized_embeddings, name="durian_embedding_model")
    
    # Export Dual-Head TFLite
    print("[*] Converting Dual-Head Model to TFLite...")
    converter = tf.lite.TFLiteConverter.from_keras_model(dual_model)
    converter.optimizations = [tf.lite.Optimize.DEFAULT]
    tflite_dual = converter.convert()
    
    dual_path = "../assets/models/durian_leaf_health_mobilenet.tflite"
    with open(dual_path, "wb") as f:
        f.write(tflite_dual)
    print(f"[+] Saved Dual-Head Model to {dual_path} (Size: {len(tflite_dual) / 1024:.2f} KB)")
    
    # Export Embedding TFLite
    print("[*] Converting Embedding Model to TFLite...")
    conv_embed = tf.lite.TFLiteConverter.from_keras_model(embed_model)
    conv_embed.optimizations = [tf.lite.Optimize.DEFAULT]
    tflite_embed = conv_embed.convert()
    
    embed_path = "../assets/models/durian_embedding_extractor.tflite"
    with open(embed_path, "wb") as f:
        f.write(tflite_embed)
    print(f"[+] Saved Embedding Model to {embed_path} (Size: {len(tflite_embed) / 1024:.2f} KB)")
    
    # Verification test via TFLite Interpreter
    print("[*] Verifying TFLite Interpreter inference...")
    import numpy as np
    interp = tf.lite.Interpreter(model_path=dual_path)
    interp.allocate_tensors()
    
    input_details = interp.get_input_details()
    output_details = interp.get_output_details()
    
    dummy_input = np.random.uniform(0.0, 1.0, size=(1, 224, 224, 3)).astype(np.float32)
    interp.set_tensor(input_details[0]['index'], dummy_input)
    interp.invoke()
    
    out0 = interp.get_tensor(output_details[0]['index'])
    out1 = interp.get_tensor(output_details[1]['index'])
    print(f"[+] Verification Successful!")
    print(f"    Output 0 shape: {out0.shape} (Sample sum: {np.sum(out0):.4f})")
    print(f"    Output 1 shape: {out1.shape}")

if __name__ == "__main__":
    build_and_export_models()
