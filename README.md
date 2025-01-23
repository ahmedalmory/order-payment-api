# Order and Payment Management API

This is a Laravel-based API for managing orders and payments. It supports extensible payment gateways and adheres to RESTful principles.

---

## **Features**
- **Order Management**: Create, update, delete, and view orders.
- **Payment Management**: Process payments for orders using multiple gateways.
- **Authentication**: Secure API endpoints using JWT authentication.
- **Extensibility**: Easily add new payment gateways using the strategy pattern.

---

## **Setup Instructions**

### **1. Clone the Repository**
```bash
git clone https://github.com/ahmedalmory/order-payment-api.git
cd order-payment-api
```

### **2. Install Dependencies**
```bash
composer install
```

### **3. Set Up the Database**
- Update the `.env` file with your database credentials:
  ```env
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=order_payment_api
  DB_USERNAME=root
  DB_PASSWORD=
  ```

- Run migrations to create the required tables:
  ```bash
  php artisan migrate
  ```

### **4. Generate JWT Secret Key**
```bash
php artisan jwt:secret
```

### **5. Start the Development Server**
```bash
php artisan serve
```

The API will be available at `http://localhost:8000`.

---

## **API Endpoints**

### **Authentication**
- **Register User**: `POST /api/register`
  ```json
  {
      "name": "Ahmed Almory",
      "email": "ahmedalmory99@gmail.com",
      "password": "password"
  }
  ```

- **Login User**: `POST /api/login`
  ```json
  {
      "email": "ahmedalmory99@gmail.com",
      "password": "password"
  }
  ```

### **Orders**
- **Create Order**: `POST /api/orders`
  ```json
  {
      "user_id": 1,
      "items": [
          {
              "product_name": "Product 1",
              "quantity": 2,
              "price": 10.00
          },
          {
              "product_name": "Product 2",
              "quantity": 1,
              "price": 20.00
          }
      ]
  }
  ```

- **Delete Order**: `DELETE /api/orders/{id}`

- **View Orders**: `GET /api/orders`

- **View Single Order**: `GET /api/orders/{id}`

### **Payments**
- **Process Payment**: `POST /api/payments`
  ```json
  {
      "order_id": 1,
      "payment_method": "credit_card"
  }
  ```

- **View Payments**: `GET /api/payments`

---

## **Payment Gateway Extensibility**

The system is designed to allow easy addition of new payment gateways using the **Strategy Pattern**. Here’s how to add a new payment gateway:

### **1. Create a New Gateway Class**
Create a new class in the `app/Services/PaymentGateways` directory that implements the `PaymentGateway` interface. For example, create `NewGateway.php`:
```php
namespace App\Services\PaymentGateways;

use App\Interfaces\PaymentGateway;

class NewGateway implements PaymentGateway {
    public function processPayment($amount, $orderId) {
        // Implement payment processing logic here
        return 'successful';
    }
}
```

### **2. Update the PaymentGatewayFactory**
Add the new gateway to the `PaymentGatewayFactory`:
```php
namespace App\Services;

use App\Interfaces\PaymentGateway;
use App\Services\PaymentGateways\CreditCardGateway;
use App\Services\PaymentGateways\PayPalGateway;
use App\Services\PaymentGateways\NewGateway;

class PaymentGatewayFactory {
    public static function create($gateway): PaymentGateway {
        return match ($gateway) {
            'credit_card' => new CreditCardGateway(),
            'paypal' => new PayPalGateway(),
            'new_gateway' => new NewGateway(),
            default => throw new \InvalidArgumentException('Invalid payment gateway'),
        };
    }
}
```

### **3. Use the New Gateway**
When processing a payment, specify the new gateway in the request:
```json
{
    "order_id": 1,
    "payment_method": "new_gateway"
}
```

---

## **Additional Notes and Assumptions**

### **Business Rules**
- Payments can only be processed for orders in the `confirmed` status.
- Orders cannot be deleted if they have associated payments.

### **Testing**
- Run the tests using:
  ```bash
  php artisan test
  ```

### **Postman Collection**
- Import the Postman collection from `OrderPaymentAPI.postman_collection.json` to test the API endpoints.

### **Environment Variables**
- Ensure the following environment variables are set in the `.env` file:
  ```env
  JWT_SECRET=your-jwt-secret-key
  APP_URL=http://localhost:8000
  ```

---

## **License**

This project is open-source and available under the MIT License.


## **Final Repository Structure**
Your repository should look like this:
```
order-payment-api/
├── app/
├── config/
├── database/
├── public/
├── resources/
├── routes/
├── tests/
├── .env.example
├── .gitignore
├── README.md
├── OrderPaymentAPI.postman_collection.json
├── composer.json
└── composer.lock
```

---