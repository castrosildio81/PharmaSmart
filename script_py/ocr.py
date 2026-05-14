import cv2
import pytesseract
import sys
import os

os.environ["TESSDATA_PREFIX"] = (
    r"C:C:\Program Files\Tesseract-OCR"
)
pytesseract.pytesseract.tesseract_cmd = r"C:\Program Files\Tesseract-OCR\tesseract.exe"
imagem_path = sys.argv[1]

img = cv2.imread(imagem_path)

if img is None:
    print("Erro: imagem não encontrada")
    exit()

gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)

thresh = cv2.threshold(gray, 150, 255, cv2.THRESH_BINARY)[1]

texto = pytesseract.image_to_string(
    thresh,
    lang='por',
    config='--psm 6'
)

print(texto)
