### **Warehouse Control**

A basic and lightweight administrative inventory system, perfect for small and medium-sized businesses that need to maintain precise control over their products. This project allows you to record inventory movements, keep a detailed history, and generate downloadable reports in Excel format.

-----

### **Key Features**

  * **Inventory Control**: Records product inflows and outflows to keep inventory up to date.
  * **Inventory History**: Maintains a historical log of all movements for complete traceability.
  * **Report Generation**: Exports detailed inventory reports in `.xlsx` format using the **PhpSpreadsheet** library.

-----

### **System Requirements**

To run this project, you need a development environment with the following components installed:

  * **PHP** (version 8.2 or higher)
  * **Composer** (PHP dependency manager)
  * A local web server (such as XAMPP, WAMP, or MAMP)

-----

### **Installation and Setup**

Follow these steps to clone the repository and set up the project:

1.  **Clone the repository:**

    ```sh
    git clone https://github.com/oigonzalezp2024/control-bodega.git
    cd control-bodega
    ```

2.  **Install dependencies with Composer:**

    ```sh
    composer install
    ```

    > **⚠️ Having installation issues?** If you get errors related to missing `ext-gd` or `ext-zip`, or a PHP version error (`php >=8.2`), check the **Troubleshooting** section below.

3.  **Configure your local server:**

      * Move or link the project folder to your web server's root directory (usually `htdocs` in XAMPP or `www` in WAMP).
      * Make sure Apache and PHP are running in your environment.

-----

### **How to Test the Project**

To test the system, simply navigate to the project folder via your local server's URL.

  * **Access URL**:
    ```
    http://localhost/warehouse-control
    ```

The project should load on the main page, where you can start recording products and managing your inventory.

-----

### **Troubleshooting**

If the Composer installation failed, your PHP environment is likely not configured correctly. Here are the solutions to the most common problems:

#### **Enable Missing PHP Extensions**

1.  Open the **XAMPP Control Panel**.

2.  Next to **Apache**, click **Config** and select **PHP (php.ini)**.

3.  In the `php.ini` file, find and remove the semicolon (`;`) at the beginning of the following lines:

    ```ini
    ;extension=gd
    ;extension=zip
    ```

4.  **Save the changes** and **restart Apache** from the XAMPP Control Panel for the extensions to take effect.

#### **Update Your PHP Version**

If the error indicates that your PHP version is too old (`php >=8.2`), the solution is to update your development environment.

  * Download the latest version of **XAMPP** from the [official Apache Friends website](https://www.apachefriends.org/index.html).

-----

### **Contributions**

Contributions are welcome\! Feel free to open an *issue* to report bugs or suggest improvements, or submit a *pull request* with your changes.

-----

### **Author**

  * **Oscar Gonzalez** - [oigonzalezp2024](https://github.com/oigonzalezp2024)

-----

### **License**

This project is licensed under the **MIT License**. For more details, see the `LICENSE` file in the repository.