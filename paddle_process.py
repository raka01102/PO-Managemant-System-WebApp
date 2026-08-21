import os
import sys
import json
from paddleocr import PPStructure

# Load sekali saja (GLOBAL)
table_engine = PPStructure(
    show_log=False,
    lang='en',
    structure_version='PP-StructureV2'
)

def main():
    if len(sys.argv) < 2:
        return

    image_path = sys.argv[1]

    if not os.path.exists(image_path):
        print(json.dumps({"error": "File not found"}))
        return

    result = table_engine(image_path)

    print(json.dumps(result, ensure_ascii=False))

if __name__ == "__main__":
    main()
