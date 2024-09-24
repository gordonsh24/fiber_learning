#!/bin/bash

# File name for the generated CSV
OUTPUT_FILE="large_file3.csv"

# Number of rows to generate (adjust as needed)
NUM_ROWS=1500000  # Generates 1 million rows

# Number of columns in the CSV
NUM_COLUMNS=10

# Generate the header
echo "Generating header..."
header=""
for ((i=1; i<=NUM_COLUMNS; i++)); do
    header+="Column$i"
    if [ $i -lt $NUM_COLUMNS ]; then
        header+=","
    fi
done
echo "$header" > "$OUTPUT_FILE"

# Generate the data rows
echo "Generating data rows..."
for ((j=1; j<=NUM_ROWS; j++)); do
    row=""
    for ((k=1; k<=NUM_COLUMNS; k++)); do
        # Generate a random number
        value=$((RANDOM))
        row+="$value"
        if [ $k -lt $NUM_COLUMNS ]; then
            row+=","
        fi
    done
    echo "$row" >> "$OUTPUT_FILE"

    # Optional: Display progress every 100,000 rows
    if (( j % 100000 == 0 )); then
        echo "$j rows generated..."
    fi
done

echo "CSV file '$OUTPUT_FILE' with $NUM_ROWS rows generated successfully."