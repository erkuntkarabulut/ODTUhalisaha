#!/bin/bash

output_file="merged_project.txt"
> "$output_file"  # Çıktı dosyasını temizle (varsa)

find . -type f \( -name "*.txt" -o -name "*.php" -o -name "*.html" -o -name "*.css" -o -name "*.js" \) | while read -r file
do
    case "$file" in
        *.php) echo "###// $file" >> "$output_file" ;;
        *.html) echo "###<!-- $file -->" >> "$output_file" ;;
        *.css) echo "###/* $file */" >> "$output_file" ;;
        *.js) echo "###// $file" >> "$output_file" ;;
        *.txt) echo "### $file" >> "$output_file" ;;
    esac

    cat "$file" >> "$output_file"
    echo "" >> "$output_file"  # Dosyalar arasında boşluk bırak
done

echo "Dosyalar başarıyla birleştirildi: $output_file"

