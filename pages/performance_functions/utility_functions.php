<?php
// Function to calculate the completion ratio
function calculateCompletionRatio($total, $target)
{
    $result = $target > 0 ? ($total / $target) * 100 : 0;
    return number_format($result, 0);
}

function splitArrayInto2D($inputArray, $n)
{
    $output2DArray = [];

    // Number of columns you want in each inner array
    $numColumns = count($inputArray) / $n;

    // Calculate the number of rows needed (ceil of array length divided by numColumns)
    $numRows = ceil(count($inputArray) / $numColumns);

    // Initialize the 2D array with the correct structure
    for ($i = 0; $i < $numColumns; $i++) {
        $output2DArray[] = [];
    }

    // Fill the 2D array by iterating over the input array
    for ($index = 0; $index < count($inputArray); $index++) {
        // Calculate the current column based on the index
        $column = $index % $numColumns;
        // Calculate the current row based on the index
        $row = intdiv($index, $numColumns);
        // Add the current element to the correct position in the 2D array
        $output2DArray[$column][$row] = $inputArray[$index];
    }

    return $output2DArray;
}
?>