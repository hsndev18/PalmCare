import base64
import logging
import os

import numpy as np
from dotenv import load_dotenv
from flask import Flask, jsonify, request
from openai import OpenAI
from tensorflow.keras.models import load_model
from tensorflow.keras.preprocessing import image

# Initialize Flask app
app = Flask(__name__)

# Setup logging
logging.basicConfig(
    filename="logfile.log",
    level=logging.INFO,
    format="%(asctime)s - %(levelname)s - %(message)s",
)

# Load environment variables for OpenAI API key
load_dotenv()
api_key = os.getenv("OPENAI_API_KEY")
if not api_key:
    logging.error("API key is not set.")
    raise ValueError("API key is not set.")

# Initialize OpenAI
client = OpenAI(api_key=api_key)

# Load the plant disease detection model
MODEL_PATH = "Model/plant_disease_model.h5"
disease_model = load_model(MODEL_PATH)


# Prepare the image for the model
def prepare_image(image_path):
    img = image.load_img(image_path, target_size=(256, 256))  # Match model requirements
    img_array = image.img_to_array(img)
    img_array = np.expand_dims(img_array, axis=0)  # Model expects batch of images
    img_array /= 255.0  # Normalize the image
    return img_array


# Analyze image using the custom model
def analyze_image_with_custom_model(image_path):
    img_array = prepare_image(image_path)
    prediction = disease_model.predict(img_array)
    disease_index = np.argmax(prediction)  # Get the index of the highest probability
    diseases = ["Red Palm Weevil", "Black Scorch", "Root Rot"]  # Adjust diseases list
    return diseases[disease_index]


# Flask route to handle image upload and return prediction
@app.route("/analyze", methods=["POST"])
def analyze_image():
    if "image" not in request.files:
        return jsonify({"error": "No image file found"}), 400

    image_file = request.files["image"]
    image_path = os.path.join("uploads", image_file.filename)
    image_file.save(image_path)

    # Analyze the image using the model
    disease_diagnosis = analyze_image_with_custom_model(image_path)

    # Create a prompt for GPT-4
    prompt = f"Based on the image analysis, the detected disease is: {disease_diagnosis}. Please provide a detailed diagnosis in Arabic and suggest appropriate treatments."

    # Get GPT-4 response
    response = client.chat.completions.create(
        model="gpt-4",
        messages=[
            {
                "role": "system",
                "content": "أنت مساعد ذكي مختص بتشخيص أمراض وآفات مزارع النخيل.",
            },
            {"role": "user", "content": prompt},
        ],
        temperature=0.0,
    )

    diagnosis_response = response.choices[0].message.content

    # Return the GPT-4 diagnosis and treatments
    return jsonify({"disease": disease_diagnosis, "diagnosis": diagnosis_response})


# Run the Flask app
if __name__ == "__main__":
    if not os.path.exists("uploads"):
        os.makedirs("uploads")
    app.run(host="0.0.0.0", port=5000)


"""
API Explanation:
Endpoint: /analyze (POST)
Users can send an image file via a POST request.
The API analyzes the image using the model and returns the diagnosis and suggested treatments from GPT-4.
Image Handling: The uploaded image is saved in the uploads directory.
Response: A JSON object with the detected disease and GPT-4 diagnosis in Arabic is returned.

Run the API using:
python api.py

The API will be available at http://localhost:5000/analyze.

"""
