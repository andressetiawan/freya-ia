const express = require("express");
const cors = require("cors");
const puppeteer = require("puppeteer");

const app = express();
app.use(cors());
app.use(express.json());

let config = {
  browser: null,
  page: null,
};

app.post("/next", async (req, res) => {
  if (config.page != null) {
    await config.page.keyboard.press("ArrowRight");
  }

  res.json({
    action: "next",
  });
});

app.post("/back", async (req, res) => {
  if (config.page != null) {
    await config.page.keyboard.press("ArrowLeft");
  }

  res.json({
    action: "back",
  });
});

app.post("/close", async (req, res) => {
  await config.browser.close();
  config = {
    browser: null,
    page: null,
  };
  res.json(config);
});

app.post("/launch", async (req, res) => {
  const url = req.body["slideUrl"];
  if (config.browser == null) {
    config.browser = await puppeteer.launch({
      headless: false,
    });
    config.page = await config.browser.newPage();
    await config.page.goto(url, {
      waitUntil: "networkidle2",
    });
    config.page.bringToFront();
  }

  res.json({
    status: "success",
    url: url,
  });
});

app.get("/", (req, res) => {
  res.json({
    status: "Server is running!",
  });
});

app.listen(3000, () => {
  console.log("Server is running!");
});
