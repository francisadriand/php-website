const express = require("express");
const app = express();

const PORT = process.env.PORT || 3000;

app.get("/", (req, res) => {
  const name = "F";
  res.send(`
    <h1>Hello ${name} 👋</h1>
    <p>This is a dynamic website deployed online!</p>
  `);
});

app.listen(PORT, () => {
  console.log(`Server running on port ${PORT}`);
});