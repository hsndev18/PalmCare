"""
Model Prediction:

The code uses the trained .h5 model to predict the disease based on the input image.
The image is preprocessed to match the input requirements of the model (size, normalization), and the disease is identified based on the highest probability prediction.
Integration with GPT-4:

After the model predicts the disease, the result is sent to OpenAI's GPT-4 model.
The prompt includes the predicted disease, and GPT-4 generates a detailed diagnosis and treatment suggestions in Arabic.
Logging and Error Handling:
"""

import base64
import logging
import os

import numpy as np
from dotenv import load_dotenv
from openai import OpenAI
from tensorflow.keras.models import load_model
from tensorflow.keras.preprocessing import image

# Setup logging
logging.basicConfig(
    filename="logfile.log",
    level=logging.INFO,
    format="%(asctime)s - %(levelname)s - %(message)s",
)


# Initialize OpenAI
def setup_openai():
    """Initialize OpenAI API key securely."""
    load_dotenv()
    api_key = os.getenv("OPENAI_API_KEY")
    if not api_key:
        logging.error("API key is not set.")
        raise ValueError("API key is not set.")
    return OpenAI(api_key=api_key)


# Load the plant disease detection model
MODEL_PATH = "Model/plant_disease_model.h5"
disease_model = load_model(MODEL_PATH)

# Encode image to base64
def encode_image(image_path):
    with open(image_path, "rb") as image_file:
        return base64.b64encode(image_file.read()).decode("utf-8")


# Prepare the image for the model
def prepare_image(image_path):
    img = image.load_img(
        image_path, target_size=(256, 256)  # Adjust to match model requirements
    )
    img_array = image.img_to_array(img)
    img_array = np.expand_dims(img_array, axis=0)  # Model expects batch of images
    img_array /= 255.0  # Normalize the image
    return img_array


# Use the custom model to analyze the image and return the disease prediction
def analyze_image_with_custom_model(image_path):
    img_array = prepare_image(image_path)
    prediction = disease_model.predict(img_array)
    disease_index = np.argmax(prediction)  # Get the index of the highest probability
    diseases = [
        "Red Palm Weevil",
        "Black Scorch",
        "Root Rot",
    ]  # Example diseases (you should adjust this)
    return diseases[disease_index]


IMAGE_PATH = "Data/image1.jpg"
base64_image = encode_image(IMAGE_PATH)

# Custom model analysis
image_diagnosis = analyze_image_with_custom_model(IMAGE_PATH)
logging.info(f"Detected disease: {image_diagnosis}")

# OpenAI GPT call for additional advice
client = setup_openai()
prompt = f"Based on the image analysis, the detected disease is: {image_diagnosis}. Please provide a detailed diagnosis in Arabic and suggest appropriate treatments."

response = client.chat.completions.create(
    model="gpt-4",  # Use the appropriate model
    messages=[
        {
            "role": "system",
            "content": "أنت مساعد ذكي مختص بتشخيص أمراض وآفات مزارع النخيل.",
        },
        {"role": "user", "content": prompt},
    ],
    temperature=0.0,
)

# Output GPT result
print(response.choices[0].message.content)
