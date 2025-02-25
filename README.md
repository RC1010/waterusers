# waterusers
Project for Building Water Webpage Business

Here are the key technologies, techniques, and functionalities I've used:

Technologies & Tools Used:
1. PHP - Server-side scripting language for processing requests and interacting with the database.
2. MySQL (via PDO) - Database for storing user, customer, order, and role-related data.
3. HTML - Structuring the web pages.
4. CSS - Styling the pages using external stylesheets.
5. JavaScript - Handling client-side interactions.
6. Font Awesome & Boxicons - Icon libraries for UI enhancement.
7. Bootstrap Dialog (via CDN) - For modal dialogs (though not fully used in my shared code).
8. Chart.js - JavaScript library used for rendering interactive graphs to visualize order and customer data.

Functionalities Implemented:
1. Session Management:
  - Uses session_start() to maintain user sessions.
  - Checks if a user is logged in before allowing access to pages.
  - Stores user_id and role_id in the session for authorization.

2. Database Operations (Using PDO):
  - CRUD Operations:
    - SELECT COUNT(*) for counting users, roles, customers, and orders.
    - SUM(amount) for calculating total order amounts.
    - INSERT INTO for adding customer orders.
    - JOIN queries to fetch related data from multiple tables.
    - Uses try-catch for exception handling in database queries.
      
3. Pagination:
  - Uses LIMIT and OFFSET in SQL queries to paginate customer_orders.
  - Calculates total pages dynamically.
    
4. Security Measures:
  - Uses prepared statements ($stmt->bindValue()) to prevent SQL injection.
  - Uses htmlspecialchars() to prevent XSS (Cross-Site Scripting) in user input/output.
  - Redirects unauthorized users to index.php.
    
5. File Inclusions:
  - require_once './database/database.php' for database connection.
  - include("nav.php") for reusable navigation.
  - include ("graph/graph.php") for embedding graphs.
    
6. Dynamic Content:
  - Displays real-time counts of users, roles, customers, and orders.
  - Formats order amounts using number_format().
  - Displays customer orders with timestamps formatted (date('M d, Y h:i:s A')).
    
7. Form Handling & Data Submission:
  - Handles form submission via $_POST for adding new orders.
  - Uses header('Location: '.$_SERVER['PHP_SELF']); to refresh the page after form submission.

Potential Enhancements:
  - Input Validation: Use filter_var() for stricter input validation.
  - Role-Based Access Control (RBAC): Restrict certain actions based on role_id.
  - AJAX for Pagination: Improve UX by loading paginated content dynamically without reloading the page.
  - Error Logging: Implement logging instead of die("Error: " . $e->getMessage()); for better debugging.
