<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footer</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        } 
        
        footer {
            background-color: #333;
            color: #fff;
            width: 100%;
            height: auto;
            display: flex;
            margin-top: 2vh;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .footer-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            justify-content: center;
            padding: 50px 150px;
            background-color: #333;
            color: #fff;
            height: auto;
            width: 100%;
        }

        .contact-us ul {
            list-style: none;
            padding: 0;
        }

        .contact-us ul li {
            margin: 10px 0;
            padding: 5px;
        }

        .contact-us ul li a {
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .contact-us ul li a img {
            width: 24px;
            height: 24px;
            margin-right: 10px;
        }

        .feedback-container input {
            display: block;
            width: 100%;
            margin: 10px 0;
            border: none;
            border-radius: 8px;
            padding: 10px;
            box-sizing: border-box;
        }

        .feedback-container input[type="text"] {
            height: 150px;
        }

        .submit-btn {
            background-color: #fff;
            color: #333;
            border: none;
            padding: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
            border-radius: 5px;
        }

        .submit-btn:hover {
            background-color: #ddd;
        }

        @media (max-width: 768px) {
            nav {
                flex-direction: column;
                align-items: flex-start;
            }

            .options {
                margin-top: 10px;
            }

            .search-section {
                margin-top: 10px;
            }

            .footer-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<footer>
        <section class="footer-container">
            <div class="contact-us">
                <h2>Contact Us</h2>
                <div class="contact-us-container">
                    <ul type="none">
                        <li><a href="https://www.instagram.com/bleach_tbh?igsh=cTBmM20zM2M4OWFs"><img src="../assets/icons/instagram.png">Instagram</a></li>
                        <li><a href="#"><img src="../assets/icons/telegram.png">Telegram</a></li>
                        <li><a href="#"><img src="../assets/icons/twitter.png">X</a></li>
                        <li><a href="#"><img src="../assets/icons/facebook.png">Facebook</a></li>
                        <li><a href="#"><img src="../assets/icons/gmail.png">G-Mail</a></li>
                    </ul>
                </div>
            </div>

            <div class="feedback-form">
                <h2>Feedback</h2>
                <form action="" class="feedback-container" name="feedback-form" method="post">
                    <input type="email" name="emailid" class="email-feedback" placeholder="E-Mail">
                    <input type="text" name="feedback-text" class="text-feedback" placeholder="Feedback">
                    <input type="button" value="Submit" name="submitok" class="submit-btn">
                </form>
            </div>
        </section>
        <p style="height: 5vh; width: 100%; display: flex; align-items:center; justify-content:center; color:#fff;">&copy; Group No.2</p>
    </footer>
</body>
</html>
