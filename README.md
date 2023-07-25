# Skripsi Arip - Web Application for Thesis Process

**Project Description: PharmaTrend - Predicting Medicine Stock using K-Means and Trend Moment**

*PharmaTrend* is an innovative web application developed using PHP, Tailwind CSS, and HTML, aimed at addressing the challenges faced by Pharmaceutical Wholesale Distributors (PBF) in accurately forecasting medicine demand and managing stock efficiently. In the highly competitive business landscape, the timely processing and delivery of information are critical factors for a company's success, and PharmaTrend aims to provide a reliable solution to enhance efficiency in data processing.

**Objectives:**
The primary objective of *PharmaTrend* is to implement the K-Means clustering method and Trend Moment technique to predict medicine stock at PT. Lestari Jaya Farma. Through K-Means clustering, the application categorizes data into high, medium, and low clusters, enabling a better understanding of medicine demand patterns. The Trend Moment method is then applied to forecast demand trends based on historical sales data, facilitating effective inventory management.

**Methodology:**
The project involves two key methodologies:
1. **K-Means Clustering:** This method is utilized to cluster the data, classifying 100 medicine items into high, medium, and low-demand categories. The clustering results in 42 high-demand, 32 medium-demand, and 24 low-demand medicine items, allowing PT. Lestari Jaya Farma to prioritize their inventory and respond to customer needs efficiently.

2. **Trend Moment Prediction:** The Trend Moment technique is employed to predict demand trends based on historical sales data. By analyzing past sales patterns, PharmaTrend provides accurate forecasts, helping the company optimize their inventory levels and minimize stockouts.

**Results and Impact:**
The data processing results in PharmaTrend have been impressive. The Trend Moment predictions were evaluated, revealing a Mean Absolute Percentage Error (MAPE) of 1.30% and an accuracy rate of 98.70%. These remarkable results demonstrate the effectiveness and reliability of the predictive capabilities of the application.

**Conclusion:**
*PharmaTrend* showcases the successful implementation of advanced data processing techniques to predict medicine stock and demand trends for PT. Lestari Jaya Farma. By utilizing K-Means clustering and Trend Moment prediction, the application empowers the company to make informed decisions, streamline inventory management, and ensure a seamless supply chain. It revolutionizes the way pharmaceutical distributors handle their stock and contributes significantly to PT. Lestari Jaya Farma's success in the highly competitive market.

*Join PharmaTrend today to stay ahead in the pharmaceutical industry and revolutionize your inventory management!*

## Preparation for the project

1. Install NodeJS, website: <https://nodejs.org/en/>
2. Install XAMPP, website: <https://www.apachefriends.org/download.html>
3. Install Git, website: <https://git-scm.com/downloads>
4. Install Visual Studio Code, website: <https://code.visualstudio.com/download>
5. Install Extensions Taiwind CSS IntelliSense, website: <https://marketplace.visualstudio.com/items?itemName=bradlc.vscode-tailwindcss>
6. Install Extensions Live Server, website: <https://marketplace.visualstudio.com/items?itemName=ritwickdey.LiveServer>
7. Install Extensions PostCSS Language Support, website: <https://marketplace.visualstudio.com/items?itemName=csstools.postcss>

## How to clone the project

1. Open the terminal
2. Change the current working directory to the location where you want the cloned directory.
3. Write the command `git clone https://github.com/ikimukti/skripsi-arip.git`.
4. Change the current working directory to the cloned directory `cd skripsi-arip`.
5. Open the project in Visual Studio Code with the command `code .`.
6. `npm install -D tailwindcss@latest postcss@latest autoprefixer@latest` to install tailwindcss

    ```text
    added 92 packages, and audited 93 packages in 2s

    19 packages are looking for funding
    run `npm fund` for details

    found 0 vulnerabilities
    ```

7. `npx tailwindcss init -p` to create tailwind.config.js

    ```text
    tailwindcss 2.2.7

    ✅ Created Tailwind config file: tailwind.config.js
    ✅ Created PostCSS config file: postcss.config.js
    ```

    or

    ```text
    tailwind.config.js already exists.
    postcss.config.js already exists.
    ```

8. `npx tailwindcss build src/css/input.css -o public/css/output.css` to create public/styles.css

    ```text
    tailwindcss 2.2.7

    ✅ Generated public/css/output.css successfully.
    ```

    or

    ```text
    public/css/output.css already exists.
    ```

9. `npm run build` to create public/bundle.js or `npm run dev` to create public/bundle.js and watch for changes
10. Open the project in Visual Studio Code with the command `code .`
11. Run XAMPP and start Apache and MySQL
12. Open the browser and go to `http://localhost/phpmyadmin/`
13. Create a database with the name `skripsi-arip`
14. import the database file `skripsi-arip.sql` in the database folder
15. Open web browser icognito mode and go to `http://localhost/skripsi-arip/public/`
