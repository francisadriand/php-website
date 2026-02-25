<?php
session_start();
$conn = include 'db.php';

if (isset($_POST['transfer']))
{
    $sender = $_POST['sender'];
    $receiver = $_POST['receiver'];
    $amount = $_POST['amount'];

    $sql = "CALL ProcessTransfer($sender, $receiver, $amount)";
    if ($conn->query($sql) === TRUE)
    {
        echo "<script>alert('Transfer successful!');</script>";
    }
    else
    {
        echo "<script>alert('Transfer failed: " . $conn->error . "');</script>";
    }
}

if (isset($_POST['transferTwo']))
{
    $sender = $_POST['sender'];
    $receiver = $_POST['receiver'];
    $amount = $_POST['amount'];

    $sql = "CALL ProcessTransferCheck($sender, $receiver, $amount, @msg)";
    try
        {
            if ($conn->query($sql) === TRUE)
        {
            $result = $conn->query("SELECT @msg AS message");
            $row = $result->fetch_assoc();
            echo "<script>alert('" . $row['message'] . "');</script>";
        }
        else
        {
            echo "<script>alert('Transfer failed: " . $conn->error . "');</script>";
        }
    }
    catch (Exception $e)
    {
        echo "<script>alert('An error occurred: " . $e->getMessage() . "');</script>";
    }
}

if (isset($_POST['transferThree']))
{
    $sender = $_POST['sender'];
    $receiver = $_POST['receiver'];
    $amount = $_POST['amount'];

    $sql = "CALL ProcessTransferBonus($sender, $receiver, $amount, @msg)";
    try
    {
        if ($conn->query($sql) === TRUE)
        {
            $result = $conn->query("SELECT @msg AS message");
            $row = $result->fetch_assoc();
            echo "<script>alert('" . $row['message'] . "');</script>";
        }
        else
        {
            echo "<script>alert('Transfer failed: " . $conn->error . "');</script>";
        }
    }
    catch (Exception $e)
    {
        echo "<script>alert('An error occurred: " . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwiftPay Test</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <a href="index.php">SwiftPay</a>
        <a href="transactionlogs.php">Transaction Logs</a>
    </header>
    <div class="main-container">
        <h1>SwiftPay Accounts</h1>
        <table>
            <tr>
                <th class="table-headers">Account Number</th>
                <th class="table-headers">Account Name</th>
                <th class="table-headers">Balance</th>
            </tr>
            <?php
            $sql = "SELECT * FROM accounts";
            $result = $conn->query($sql);
            if ($result->num_rows > 0)
            {
                while($row = $result->fetch_assoc())
                {
                    echo "<tr><td>" . $row["AccountID"] . "</td><td>" . $row["Username"] . "</td><td>$" . number_format($row["Balance"], 2) . "</td></tr>";
                }
            }
            else
            {
                echo "<tr><td colspan='3'>No accounts found</td></tr>";
            }
            ?>
        </table>

        <div class="phases">
            <div class="phaseTables">
                <form action="index.php" method="POST">
                    <h2>Basic Transfer (Phase 1)</h2>
                    <label for="sender">Sender: </label>
                    <input type="text" placeholder="Enter account number" id="sender" name="sender" required><br><br>
                    <label for="receiver">Receiver: </label>
                    <input type="text" placeholder="Enter account number" id="receiver" name="receiver" required><br><br>
                    <label for="amount">Amount: </label>
                    <input type="number" placeholder="Enter amount" id="amount" name="amount" step="0.01" required><br><br>
                    <button type="submit" name="transfer">Transfer</button>
                </form>
            </div>

            <div class="phaseTables">
                <form action="index.php" method="POST">
                    <h2>Advanced Transfer (Phase 2)</h2>
                    <label for="sender">Sender: </label>
                    <input type="text" placeholder="Enter account number" id="sender" name="sender" required><br><br>
                    <label for="receiver">Receiver: </label>
                    <input type="text" placeholder="Enter account number" id="receiver" name="receiver" required><br><br>
                    <label for="amount">Amount: </label>
                    <input type="number" placeholder="Enter amount" id="amount" name="amount" step="0.01" required><br><br>
                    <button type="submit" name="transferTwo">Transfer</button>
                </form>
            </div>

            <div class="phaseTables">
                <form action="index.php" method="POST">
                    <h2>Transfer With Bonus (Phase 3)</h2>
                    <label for="sender">Sender: </label>
                    <input type="text" placeholder="Enter account number" id="sender" name="sender" required><br><br>
                    <label for="receiver">Receiver: </label>
                    <input type="text" placeholder="Enter account number" id="receiver" name="receiver" required><br><br>
                    <label for="amount">Amount: </label>
                    <input type="number" placeholder="Enter amount" id="amount" name="amount" step="0.01" required><br><br>
                    <button type="submit" name="transferThree">Transfer</button>
                </form>
            </div>
        </div>
    </div>

    <input type="text" id="message">
    <button onclick="saveData()">Save</button>

    <footer>
        <p>&copy; 2024 SwiftPay. All rights reserved.</p>
    </footer>

    <!-- <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
        import { getFirestore, collection, addDoc } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-firestore.js";

        const firebaseConfig = {
            apiKey: "AIzaSyATGDinwBxUwZO8gkhmtzvoxTDAmFn1-hI",
            authDomain: "my-sample-cc95a.firebaseapp.com",
            projectId: "my-sample-cc95a",
        };

        const app = initializeApp(firebaseConfig);
        const db = getFirestore(app);

        window.saveData = async function() {
            const msg = document.getElementById("message").value;

            await addDoc(collection(db, "messages"), {
            text: msg,
            created: new Date()
            });

            alert("Saved!");
        }
    </script> -->
</body>
<!-- <script type="module">
  // Import the functions you need from the SDKs you need
  import { initializeApp } from "https://www.gstatic.com/firebasejs/12.9.0/firebase-app.js";
  import { getAnalytics } from "https://www.gstatic.com/firebasejs/12.9.0/firebase-analytics.js";
  // TODO: Add SDKs for Firebase products that you want to use
  // https://firebase.google.com/docs/web/setup#available-libraries

  // Your web app's Firebase configuration
  // For Firebase JS SDK v7.20.0 and later, measurementId is optional
  const firebaseConfig = {
    apiKey: "AIzaSyATGDinwBxUwZO8gkhmtzvoxTDAmFn1-hI",
    authDomain: "my-sample-cc95a.firebaseapp.com",
    databaseURL: "https://my-sample-cc95a-default-rtdb.firebaseio.com",
    projectId: "my-sample-cc95a",
    storageBucket: "my-sample-cc95a.firebasestorage.app",
    messagingSenderId: "987232941668",
    appId: "1:987232941668:web:a4d9bd525713db969d5171",
    measurementId: "G-CZ4TCMPVE6"
  };

  // Initialize Firebase
  const app = initializeApp(firebaseConfig);
  const analytics = getAnalytics(app);
</script> -->
</html>