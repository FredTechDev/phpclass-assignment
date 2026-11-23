<?php
/*
 * Student Grade Calculator
 * Handles both displaying the form and processing the submitted marks.
 */

// Store the values so the form can be repopulated after a submit.
$submittedValues = array(
    'cat1' => '',
    'cat2' => '',
    'exam' => ''
);

$errors = array();
$result = null;

/**
 * Return an array containing the grade letter and performance remark
 * for a given average score.
 */
function determineGrade($average)
{
    if ($average >= 70) {
        return array('grade' => 'A', 'remark' => 'Excellent');
    } elseif ($average >= 60) {
        return array('grade' => 'B', 'remark' => 'Very Good');
    } elseif ($average >= 50) {
        return array('grade' => 'C', 'remark' => 'Good');
    } elseif ($average >= 40) {
        return array('grade' => 'D', 'remark' => 'Pass');
    } else {
        return array('grade' => 'E', 'remark' => 'Fail');
    }
}

// Process the form only when it was submitted with POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fieldLabels = array(
        'cat1' => 'CAT 1',
        'cat2' => 'CAT 2',
        'exam' => 'Final Examination'
    );

    // Retrieve and clean the submitted values.
    foreach ($fieldLabels as $field => $label) {
        if (isset($_POST[$field])) {
            $submittedValues[$field] = trim(strip_tags((string) $_POST[$field]));
        }
    }

    // 1 & 2. Check that each value exists, is numeric and within the allowed range.
    foreach ($fieldLabels as $field => $label) {
        $mark = $submittedValues[$field];

        if ($mark === '') {
            $errors[] = $label . ' is required.';
        } elseif (!is_numeric($mark)) {
            $errors[] = $label . ' must be a number.';
        } elseif ($mark < 0 || $mark > 100) {
            $errors[] = $label . ' must be between 0 and 100.';
        }
    }

    // If everything is valid, perform the calculations.
    if (empty($errors)) {
        $cat1 = (float) $submittedValues['cat1'];
        $cat2 = (float) $submittedValues['cat2'];
        $exam = (float) $submittedValues['exam'];

        $total = $cat1 + $cat2 + $exam;
        $average = $total / 3;
        $gradeInfo = determineGrade($average);

        $result = array(
            'cat1' => $cat1,
            'cat2' => $cat2,
            'exam' => $exam,
            'total' => $total,
            'average' => $average,
            'grade' => $gradeInfo['grade'],
            'remark' => $gradeInfo['remark']
        );
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Grade Calculator</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>Student Grade Calculator</h1>
            <p class="description">Enter the three assessment marks (0 - 100) to calculate the total, average and grade.</p>

            <?php if (!empty($errors)): ?>
                <div class="server-errors">
                    <strong>Please correct the following:</strong>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form id="gradeForm" action="index.php" method="post" novalidate>
                <div class="form-group">
                    <label for="cat1">CAT 1</label>
                    <input type="number" id="cat1" name="cat1" step="any"
                           value="<?php echo htmlspecialchars($submittedValues['cat1'], ENT_QUOTES, 'UTF-8'); ?>">
                    <span class="error" id="cat1-error"></span>
                </div>

                <div class="form-group">
                    <label for="cat2">CAT 2</label>
                    <input type="number" id="cat2" name="cat2" step="any"
                           value="<?php echo htmlspecialchars($submittedValues['cat2'], ENT_QUOTES, 'UTF-8'); ?>">
                    <span class="error" id="cat2-error"></span>
                </div>

                <div class="form-group">
                    <label for="exam">Final Examination</label>
                    <input type="number" id="exam" name="exam" step="any"
                           value="<?php echo htmlspecialchars($submittedValues['exam'], ENT_QUOTES, 'UTF-8'); ?>">
                    <span class="error" id="exam-error"></span>
                </div>

                <button type="submit">Calculate Grade</button>
            </form>
        </div>

        <?php if ($result !== null): ?>
            <div class="card">
                <h2>Calculation Results</h2>

                <div class="result-row">
                    <span class="result-label">CAT 1</span>
                    <span><?php echo number_format($result['cat1'], 2); ?></span>
                </div>
                <div class="result-row">
                    <span class="result-label">CAT 2</span>
                    <span><?php echo number_format($result['cat2'], 2); ?></span>
                </div>
                <div class="result-row">
                    <span class="result-label">Final Examination</span>
                    <span><?php echo number_format($result['exam'], 2); ?></span>
                </div>
                <div class="result-row">
                    <span class="result-label">Total accumulated marks</span>
                    <span><?php echo number_format($result['total'], 2); ?> / 300</span>
                </div>
                <div class="result-row">
                    <span class="result-label">Average score</span>
                    <span><?php echo number_format($result['average'], 2); ?> / 100</span>
                </div>
                <div class="result-row">
                    <span class="result-label">Grade</span>
                    <span class="grade-badge"><?php echo htmlspecialchars($result['grade'], ENT_QUOTES, 'UTF-8'); ?></span>
                </div>
                <div class="result-row">
                    <span class="result-label">Performance remark</span>
                    <span><?php echo htmlspecialchars($result['remark'], ENT_QUOTES, 'UTF-8'); ?></span>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="script.js"></script>
</body>
</html>
