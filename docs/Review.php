<?php
session_start();
require_once(__DIR__ . '/api/config/db_config.php');

// Fetch approved feedback with customer names (using new customer_feedback table)
$sql = "SELECT 
            cf.FeedbackID,
            cf.CustomerID,
            CASE 
                WHEN cf.IsAnonymous = 1 THEN 'Anonymous'
                ELSE CONCAT(c.FirstName, ' ', LEFT(c.LastName, 1), '.')
            END AS DisplayName,
            cf.FeedbackType,
            cf.OrderID,
            cf.ReservationID,
            cf.OverallRating,
            cf.FoodTasteRating,
            cf.PortionSizeRating,
            cf.ServiceRating,
            cf.AmbienceRating,
            cf.CleanlinessRating,
            cf.FoodTasteComment,
            cf.PortionSizeComment,
            cf.ServiceComment,
            cf.AmbienceComment,
            cf.CleanlinessComment,
            cf.ReviewMessage,
            cf.CreatedDate,
            (SELECT GROUP_CONCAT(CONCAT(od.Quantity, 'x ', p.ProductName) SEPARATOR ', ') FROM orderdetails od JOIN products p ON od.ProductID = p.ProductID WHERE od.OrderID = cf.OrderID) AS OrderItems,
            (SELECT GROUP_CONCAT(CONCAT(Quantity, 'x ', ProductName) SEPARATOR ', ') FROM reservation_items WHERE ReservationID = cf.ReservationID) AS ReservationItems
        FROM customer_feedback cf
        JOIN customer c ON cf.CustomerID = c.CustomerID
        WHERE cf.Status = 'Approved'
        ORDER BY cf.CreatedDate DESC";

$reviews_result = $conn->query($sql);

// Get statistics from customer_feedback table
$stats_sql = "SELECT 
    COUNT(*) as total,
    COALESCE(ROUND(AVG(OverallRating), 1), 0) as avg_rating,
    COALESCE(ROUND(AVG(FoodTasteRating), 1), 0) as avg_food,
    COALESCE(ROUND(AVG(PortionSizeRating), 1), 0) as avg_portion,
    COALESCE(ROUND(AVG(ServiceRating), 1), 0) as avg_service,
    COALESCE(ROUND(AVG(AmbienceRating), 1), 0) as avg_ambience,
    COALESCE(ROUND(AVG(CleanlinessRating), 1), 0) as avg_cleanliness,
    SUM(CASE WHEN OverallRating = 5 THEN 1 ELSE 0 END) as five_star,
    SUM(CASE WHEN OverallRating = 4 THEN 1 ELSE 0 END) as four_star,
    SUM(CASE WHEN OverallRating = 3 THEN 1 ELSE 0 END) as three_star,
    SUM(CASE WHEN OverallRating = 2 THEN 1 ELSE 0 END) as two_star,
    SUM(CASE WHEN OverallRating = 1 THEN 1 ELSE 0 END) as one_star
FROM customer_feedback WHERE Status = 'Approved'";

$stats_result = $conn->query($stats_sql);
$stats = $stats_result->fetch_assoc();
?>
<?php
// Helper Function for Initials
function getInitials($name)
{
    if (!$name || $name === 'Anonymous')
        return 'A';
    $words = explode(' ', $name);
    $initials = '';
    foreach ($words as $w) {
        if (!empty($w))
            $initials .= strtoupper($w[0]);
    }
    return substr($initials, 0, 1);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews - DJs Burger Bar</title>
    <link rel="stylesheet" href="CSS/ReviewDesign.css?v=1.5">
    <link rel="stylesheet" href="CSS/Navigation.css">
    <link rel="stylesheet" href="CSS/Footer.css?v=1.4">
</head>

<body>
    <header>
        <div class="logo">
            <img src="Photo/RealLogo.png" alt="Tabeya logo">
        </div>
        <nav class="nav-links">
            <a href="index.html" class="nav-link">HOME</a>
            <a href="Menu.html" class="nav-link">MENU</a>
            <a href="CaterReservation.html" class="nav-link">RESERVATION</a>
            <a href="Review.php" class="nav-link active">REVIEWS</a>
            <a href="About.html" class="nav-link">ABOUT</a>
        </nav>
        <div class="header-right">
            <a href="Login.html" id="account-link">PROFILE</a>
            <div class="cart-icon" id="view-cart-btn">
                <a href="Cart.html" style="text-decoration: none; color: inherit;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    <span class="cart-count" id="cart-item-count">0</span>
                </a>
            </div>
        </div>
    </header>
    <section class="review-hero">
        <div class="container">
            <div class="hero-content">
                <h1>Customer Reviews</h1>
                <p>We're proud to serve the best burgers in town. See what our community has to say about their experience.</p>
                <div class="hero-stats-badge">
                    <div class="avg-score"><?php echo number_format($stats['avg_rating'], 1); ?></div>
                    <div class="avg-stars">
                        <?php
                        $rating = floatval($stats['avg_rating']);
                        for ($i = 1; $i <= 5; $i++) {
                            $filled = ($rating >= $i) ? 'filled' : '';
                            echo '<span class="star ' . $filled . '">★</span>';
                        }
                        ?>
                    </div>
                    <div class="total-reviews"><?php echo $stats['total']; ?> Reviews</div>
                </div>
            </div>
        </div>
    </section>

    <div class="testimony-container container">
        <div class="testimony-layout">
            <!-- Left Sidebar -->
            <aside class="sidebar">

                <div class="stats-card overall-card">
                    <h3>Overall Rating</h3>
                    <div class="huge-rating"><?php echo number_format($stats['avg_rating'], 1); ?></div>
                    <div class="main-stars">
                        <?php
                        $rating = floatval($stats['avg_rating']);
                        for ($i = 1; $i <= 5; $i++) {
                            $filled = ($rating >= $i) ? 'filled' : '';
                            echo '<span class="star ' . $filled . '">★</span>';
                        }
                        ?>
                    </div>
                    <p class="based-on">Based on <?php echo $stats['total']; ?> Reviews</p>

                    <div class="rating-bars">
                        <?php
                        $total = max(intval($stats['total']), 1);
                        $stars = [5 => 'five', 4 => 'four', 3 => 'three', 2 => 'two', 1 => 'one'];
                        foreach ($stars as $num => $name) {
                            $count = intval($stats[$name . '_star']) ?: 0;
                            $percent = ($count / $total) * 100;
                            echo '<div class="rating-bar-row">
                                    <span class="star-label">' . $num . ' Stars</span>
                                    <div class="bar-bg"><div class="bar-fill" style="width: ' . $percent . '%"></div></div>
                                    <span class="count-label">' . $count . '</span>
                                  </div>';
                        }
                        ?>
                    </div>
                </div>

                <div class="stats-card categories-card">
                    <h3>Category Ratings</h3>
                    <?php
                    $categories = [
                        'Food Taste' => $stats['avg_food'],
                        'Portion Size' => $stats['avg_portion'],
                        'Service' => $stats['avg_service'],
                        'Ambience' => $stats['avg_ambience'],
                        'Cleanliness' => $stats['avg_cleanliness']
                    ];
                    foreach ($categories as $label => $value) {
                        $val = floatval($value) ?: 0;
                        $percent = ($val / 5) * 100;
                        echo '<div class="category-row">
                                <span class="cat-name">' . $label . '</span>
                                <div class="cat-bar-bg"><div class="cat-bar-fill" style="width: ' . $percent . '%"></div></div>
                                <span class="cat-score">' . number_format($val, 1) . '</span>
                              </div>';
                    }
                    ?>
                </div>

                <button id="write-review-btn" class="write-btn">Write a Review</button>
            </aside>

            <!-- Main Content Area -->
            <main class="main-content">
                <div class="content-header">
                    <h2>Recent Reviews</h2>
                    <select class="sort-select">
                        <option value="recent">Recent</option>
                        <option value="oldest">Oldest</option>
                    </select>
                </div>

                <div class="reviews-list" id="reviews-container">
                    <?php
                    if ($reviews_result && $reviews_result->num_rows > 0) {
                        while ($review = $reviews_result->fetch_assoc()) {
                            $initials = getInitials($review['DisplayName']);
                            $dateAttr = date('Y-m-d H:i:s', strtotime($review['CreatedDate']));
                            echo '<div class="review-card" data-date="' . $dateAttr . '">';

                            // User Header
                            echo '<div class="review-card-header">';
                            echo '<div class="avatar">' . htmlspecialchars($initials) . '</div>';
                            echo '<div class="user-meta">';
                            echo '<div class="user-top">';
                            echo '<span class="user-name">' . htmlspecialchars($review['DisplayName']) . '</span>';
                            echo '<span class="type-badge">' . $review['FeedbackType'] . '</span>';
                            echo '</div>';

                            // Date
                            echo '<div class="review-date">' . date('M d, Y', strtotime($review['CreatedDate'])) . '</div>';

                            // Stars
                            echo '<div class="card-main-stars">';
                            for ($i = 1; $i <= 5; $i++) {
                                $filled = ($i <= $review['OverallRating']) ? 'filled' : '';
                                echo '<span class="star ' . $filled . '">★</span>';
                            }
                            echo '</div>';
                            echo '</div></div>'; // end user-meta, review-card-header
                    
                            // Display Reviewed Items
                            $reviewedItems = '';
                            if ($review['FeedbackType'] === 'Order' && !empty($review['OrderItems'])) {
                                $reviewedItems = $review['OrderItems'];
                            } elseif ($review['FeedbackType'] === 'Reservation' && !empty($review['ReservationItems'])) {
                                $reviewedItems = $review['ReservationItems'];
                            }

                            if ($reviewedItems) {
                                echo '<div class="reviewed-items-display" style="font-size: 0.9em; color: #555; margin: 5px 0 10px 60px; font-style: italic;">';
                                echo 'Ordered: ' . htmlspecialchars($reviewedItems);
                                echo '</div>';
                            }

                            // Category Badges
                            echo '<div class="category-badges">';
                            $cats = [
                                'Food' => $review['FoodTasteRating'],
                                'Portion' => $review['PortionSizeRating'],
                                'Service' => $review['ServiceRating']
                            ];
                            foreach ($cats as $label => $r) {
                                if ($r > 0) {
                                    echo '<div class="cat-badge">' . $label . ' <span class="mini-stars">' . str_repeat('★', $r) . '</span></div>';
                                }
                            }
                            echo '</div>';

                            // Message
                            if ($review['ReviewMessage']) {
                                echo '<p class="review-text">"' . htmlspecialchars($review['ReviewMessage']) . '"</p>';
                            }

                            // Accordion
                            $hasComments = $review['FoodTasteComment'] || $review['PortionSizeComment'] ||
                                $review['ServiceComment'] || $review['AmbienceComment'] ||
                                $review['CleanlinessComment'];

                            if ($hasComments) {
                                echo '<button class="accordion-btn" onclick="toggleAccordion(this)">View detailed feedback ⌵</button>';
                                echo '<div class="accordion-content">';

                                $comments = [
                                    'Food Taste' => $review['FoodTasteComment'],
                                    'Portion Size' => $review['PortionSizeComment'],
                                    'Customer Service' => $review['ServiceComment'],
                                    'Ambience' => $review['AmbienceComment'],
                                    'Cleanliness' => $review['CleanlinessComment']
                                ];

                                foreach ($comments as $label => $comment) {
                                    if ($comment) {
                                        echo '<div class="comment-group">';
                                        echo '<div class="comment-label">' . $label . '</div>';
                                        echo '<div class="comment-text">' . htmlspecialchars($comment) . '</div>';
                                        echo '</div>';
                                    }
                                }
                                echo '</div>'; // end accordion-content
                            }

                            echo '</div>'; // end review-card
                        }
                    } else {
                        echo '<p class="no-reviews-msg">No reviews yet. Be the first to share your experience!</p>';
                    }
                    ?>
                </div>

                <div class="load-more">
                    <button class="load-more-btn">Load more reviews</button>
                </div>
            </main>
        </div>
    </div>

    <!-- Review Modal -->
    <div id="review-modal" class="modal">
        <div class="modal-content">
            <span class="close-btn" id="close-modal-btn">&times;</span>
            <h2 class="modal-title">Share Your Experience</h2>

            <div id="user-info-section"></div>

            <div class="feedback-type-selection">
                <h4>What would you like to review?</h4>
                <div class="feedback-type-buttons">
                    <button class="feedback-type-btn" data-type="Order">📦 Recent Order</button>
                    <button class="feedback-type-btn" data-type="Reservation">🎉 Recent Reservation</button>
                    <button class="feedback-type-btn active" data-type="General">⭐ General Experience</button>
                </div>
            </div>

            <div class="reviewable-items-section" id="orders-section">
                <h4>Select an order to review:</h4>
                <div id="orders-list"></div>
            </div>

            <div class="reviewable-items-section" id="reservations-section">
                <h4>Select a reservation to review:</h4>
                <div id="reservations-list"></div>
            </div>

            <div class="anonymous-option">
                <input type="checkbox" id="anonymous-checkbox">
                <label for="anonymous-checkbox" class="anonymous-label">Post anonymously</label>
            </div>

            <div class="overall-rating-input">
                <h3>Overall Rating</h3>
                <div class="rating-stars modal-rating-stars" id="overall-stars">
                    <span class="rating-star" data-rating="1">★</span>
                    <span class="rating-star" data-rating="2">★</span>
                    <span class="rating-star" data-rating="3">★</span>
                    <span class="rating-star" data-rating="4">★</span>
                    <span class="rating-star" data-rating="5">★</span>
                </div>
                <div class="overall-score-display" style="margin-top: 10px; font-weight: bold; font-size: 1.2rem;">
                    Score: <span id="overall-rating-value-modal">0.0</span> / 5.0
                </div>
            </div>

            <div class="review-form-section">
                <h4 class="modal-subtitle">Rate Each Category</h4>

                <div class="rating-category">
                    <label class="cat-label">🍽️ Food Taste</label>
                    <div class="category-stars" data-category="food">
                        <span class="star" data-rating="1">★</span>
                        <span class="star" data-rating="2">★</span>
                        <span class="star" data-rating="3">★</span>
                        <span class="star" data-rating="4">★</span>
                        <span class="star" data-rating="5">★</span>
                    </div>
                </div>
                <textarea class="comment-field" id="food-comment" placeholder="Tell us about the food..."></textarea>

                <div class="rating-category">
                    <label class="cat-label">📏 Portion Size</label>
                    <div class="category-stars" data-category="portion">
                        <span class="star" data-rating="1">★</span>
                        <span class="star" data-rating="2">★</span>
                        <span class="star" data-rating="3">★</span>
                        <span class="star" data-rating="4">★</span>
                        <span class="star" data-rating="5">★</span>
                    </div>
                </div>
                <textarea class="comment-field" id="portion-comment"
                    placeholder="Was the portion satisfying?"></textarea>

                <div class="rating-category">
                    <label class="cat-label">👨‍💼 Customer Service</label>
                    <div class="category-stars" data-category="service">
                        <span class="star" data-rating="1">★</span>
                        <span class="star" data-rating="2">★</span>
                        <span class="star" data-rating="3">★</span>
                        <span class="star" data-rating="4">★</span>
                        <span class="star" data-rating="5">★</span>
                    </div>
                </div>
                <textarea class="comment-field" id="service-comment" placeholder="How was the service?"></textarea>

                <div class="rating-category">
                    <label class="cat-label">✨ Ambience</label>
                    <div class="category-stars" data-category="ambience">
                        <span class="star" data-rating="1">★</span>
                        <span class="star" data-rating="2">★</span>
                        <span class="star" data-rating="3">★</span>
                        <span class="star" data-rating="4">★</span>
                        <span class="star" data-rating="5">★</span>
                    </div>
                </div>
                <textarea class="comment-field" id="ambience-comment"
                    placeholder="Describe the atmosphere..."></textarea>

                <div class="rating-category">
                    <label class="cat-label">🧹 Cleanliness</label>
                    <div class="category-stars" data-category="cleanliness">
                        <span class="star" data-rating="1">★</span>
                        <span class="star" data-rating="2">★</span>
                        <span class="star" data-rating="3">★</span>
                        <span class="star" data-rating="4">★</span>
                        <span class="star" data-rating="5">★</span>
                    </div>
                </div>
                <textarea class="comment-field" id="cleanliness-comment"
                    placeholder="How clean was the place?"></textarea>
            </div>

            <div class="review-form-section">
                <h4 class="modal-subtitle-margin">General Comments (Optional)</h4>
                <textarea class="comment-field general-comment" id="review-message"
                    placeholder="Share your overall experience..."></textarea>
            </div>

            <button id="submit-review-btn" class="write-btn">Submit Review</button>
        </div>
    </div>

    <!-- Cart Modal Removed -->

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container footer-grid">

            <div class="footer-col">
                <h4>Follow Us</h4>
                <div class="social-icons">
                    <a href="#" aria-label="Facebook">
                        <svg xmlns="https://www.facebook.com/djburgerbar" viewBox="0 0 24 24">
                            <path
                                d="M12 2.04C6.5 2.04 2 6.53 2 12.06C2 17.06 5.66 21.21 10.44 21.96V14.96H7.9V12.06H10.44V9.85C10.44 7.34 11.93 5.96 14.22 5.96C15.31 5.96 16.45 6.15 16.45 6.15V8.62H15.19C13.95 8.62 13.56 9.39 13.56 10.18V12.06H16.34L15.89 14.96H13.56V21.96A10.05 10.05 0 0 0 22 12.06C22 6.53 17.5 2.04 12 2.04Z" />
                        </svg>
                    </a>
                    <a href="#" aria-label="Instagram">
                        <svg xmlns="https://www.instagram.com/joshua.abrantes/" viewBox="0 0 24 24">
                            <path
                                d="M7.8 2h8.4C19.4 2 22 4.6 22 7.8v8.4a5.8 5.8 0 0 1-5.8 5.8H7.8C4.6 22 2 19.4 2 16.2V7.8A5.8 5.8 0 0 1 7.8 2m-.2 2A3.6 3.6 0 0 0 3.6 7.6v8.8C3.6 18.4 5.6 20 7.6 20h8.8a3.6 3.6 0 0 0 4-3.6V7.6C20.4 5.6 18.8 4 16.8 4H7.6m4.4 3.6a4.4 4.4 0 1 1 0 8.8 4.4 4.4 0 0 1 0-8.8m0 1.6a2.8 2.8 0 1 0 0 5.6 2.8 2.8 0 0 0 0-5.6m5.8-5.6a1.4 1.4 0 1 1 0 2.8 1.4 1.4 0 0 1 0-2.8" />
                        </svg>
                    </a>
                </div>
            </div>

            <div class="footer-col">
                <h4>Visit Us</h4>
                <p>Barangay 1<br>Mercedes, Camarines Norte</p>
            </div>

            <div class="footer-col">
                <h4>Contact Us</h4>
                <p>0977 213 7648</p>
                <p>joshuafernan_abrantes@yahoo.com</p>
            </div>

            <div class="footer-col">
                <h4>All rights reserved</h4>
                <p>© 2026 DJs Burger Bar</p>
            </div>
        </div>
    </footer>

    <script src="review_enhanced.js?v=1.5"></script>
</body>

</html>
<?php $conn->close(); ?>