<?php
// Include database connection
include('../connections.php');
echo print_r($_GET);
// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get the table_id and id from the POST data
    $table_id = $_GET['table_id'] ?? '';
    $id = $_GET['id'] ?? '';
    
    // Validate table_id and id
    if (!empty($table_id) && !empty($id)) {
        // Prepare the delete query based on the table_id
        if ($table_id === 'products') {
            $delete_query = "DELETE FROM products WHERE product_id='$id'";
        } elseif ($table_id === 'accounts') {
            $delete_query = "DELETE FROM accounts WHERE account_id='$id'";
        } else {
            $delete_query = '';
        }

        // Execute the delete query
        if (!empty($delete_query) && $connections->query($delete_query)) {
            $message = 'Record deleted successfully!';
        } else {
            $message = 'Error deleting record: ' . $connections->error;
        }
    } else {
        $message = 'Invalid table or ID.';
    }

    // Redirect back to the records page with a message
    header("Location: records.php?table_id=$table_id");
    exit();
}
?>
