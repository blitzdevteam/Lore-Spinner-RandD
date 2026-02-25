#!/bin/bash

API_KEY="AIzaSyDXLKRLIvB4QmC5bLfm4pyAExCbprWO82A"
URL="https://generativelanguage.googleapis.com/v1beta/models/imagen-3.0-generate-002:predict?key=${API_KEY}"
OUT_DIR="$(cd "$(dirname "$0")" && pwd)"
TMP="${OUT_DIR}/tmp_response.json"

generate() {
    local name="$1"
    local prompt="$2"
    local outfile="${OUT_DIR}/avatar-${name}.png"

    echo "Generating ${name}..."
    curl -s -X POST "$URL" \
        -H "Content-Type: application/json" \
        -d "{\"instances\":[{\"prompt\":\"${prompt}\"}],\"parameters\":{\"sampleCount\":1}}" \
        -o "$TMP"

    img_b64=$(jq -r '.predictions[0].bytesBase64Encoded // empty' "$TMP")

    if [ -z "$img_b64" ]; then
        echo "  ERROR for ${name}: $(jq -r '.error.message // "unknown"' "$TMP")"
    else
        echo "$img_b64" | base64 --decode > "$outfile"
        echo "  Saved: $outfile ($(wc -c < "$outfile" | tr -d ' ') bytes)"
    fi
    sleep 1
}

generate "frida"     "Professional headshot photograph of a woman, late 30s, short auburn hair, wire-framed glasses, dark blazer, neutral grey studio background, soft natural lighting, confident expression, no text, photorealistic portrait"
generate "jodie"     "Professional headshot photograph of a woman, early 40s, dark wavy shoulder-length hair, burgundy blouse, soft blurred background, natural studio lighting, warm slight smile, no text, photorealistic portrait"
generate "coty"      "Professional headshot photograph of a man, early 40s, sandy blonde hair, light beard, olive green button-up shirt, warm neutral background, soft studio lighting, relaxed friendly expression, no text, photorealistic portrait"
generate "cristobal" "Professional headshot photograph of a man, mid 40s, dark curly hair, short beard, black turtleneck, dark studio background, dramatic lighting, serious expression, no text, photorealistic portrait"
generate "mckenzie"  "Professional headshot photograph of a woman, late 20s, curly red hair, cream colored blouse, warm neutral background, natural soft lighting, bright open expression, no text, photorealistic portrait"
generate "america"   "Professional headshot photograph of a woman, early 30s, sleek straight black hair, sharp features, white blouse with blazer, minimal clean background, crisp studio lighting, poised confident expression, no text, photorealistic portrait"
generate "efren"     "Professional headshot photograph of a man, late 40s, salt-and-pepper hair, clean shaven, collared shirt, neutral warm background, natural lighting, approachable confident expression, no text, photorealistic portrait"
generate "clement"   "Professional headshot photograph of a man, mid 50s, silver hair, distinguished look, navy suit jacket, classic studio background, warm professional lighting, calm thoughtful expression, no text, photorealistic portrait"

rm -f "$TMP"
echo "All done!"
