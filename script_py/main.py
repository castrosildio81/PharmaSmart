from flask import Flask, request, jsonify
import pytesseract
import cv2
import numpy as np

app = Flask(__name__)

@app.route("/ocr", methods=["POST", "GET"])
def ocr():
    print("CHEGOU NA API")

    try:
       

        if "imagem" not in request.files:
            return jsonify({"erro": "sem imagem"}), 400

        file = request.files["imagem"]

        print("IMAGEM RECEBIDA")

        img_array = np.frombuffer(file.read(), np.uint8)
        img = cv2.imdecode(img_array, cv2.IMREAD_COLOR)

        if img is None:
            return jsonify({"erro": "imagem inválida"}), 400

        gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)

        texto = pytesseract.image_to_string(gray)

        return jsonify({"texto": texto})

    except Exception as e:
        return jsonify({"erro": str(e)}), 500


if __name__ == "__main__":
    app.run(debug=True)
