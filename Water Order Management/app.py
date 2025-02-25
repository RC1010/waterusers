from flask import Flask, jsonify
from flask_cors import CORS
import mysql.connector
import pandas as pd
from datetime import datetime, timedelta

app = Flask(__name__)
CORS(app)  # Allow all domains

# Database connection
def get_db_connection():
    return mysql.connector.connect(
        host="localhost",
        user="root",
        password="",  # No password
        database="waterusers"
    )

# Fetch orders and process data
@app.route('/orders', methods=['GET'])
def get_orders():
    conn = get_db_connection()
    cursor = conn.cursor(dictionary=True)

    # 🔹 Fetch "amount" along with "created_at"
    cursor.execute("SELECT created_at, amount FROM customer_orders")
    orders = cursor.fetchall()
    conn.close()

    if not orders:
        return jsonify({"error": "No order data found"}), 404

    # Convert to DataFrame
    df = pd.DataFrame(orders)
    df['created_at'] = pd.to_datetime(df['created_at'])

    # Ensure "amount" is numeric
    df['amount'] = pd.to_numeric(df['amount'], errors='coerce').fillna(0)

    today = datetime.now()

    # Count orders
    daily_orders = df[df['created_at'].dt.date == today.date()].shape[0]
    weekly_orders = df[df['created_at'] >= today - timedelta(days=7)].shape[0]
    monthly_orders = df[df['created_at'] >= today - timedelta(days=30)].shape[0]
    yearly_orders = df[df['created_at'] >= today - timedelta(days=365)].shape[0]

    # Calculate total amounts
    daily_amount = df[df['created_at'].dt.date == today.date()]['amount'].sum()
    weekly_amount = df[df['created_at'] >= today - timedelta(days=7)]['amount'].sum()
    monthly_amount = df[df['created_at'] >= today - timedelta(days=30)]['amount'].sum()
    yearly_amount = df[df['created_at'] >= today - timedelta(days=365)]['amount'].sum()

    return jsonify({
        "daily_orders": daily_orders,
        "weekly_orders": weekly_orders,
        "monthly_orders": monthly_orders,
        "yearly_orders": yearly_orders,
        "daily_amount": daily_amount,
        "weekly_amount": weekly_amount,
        "monthly_amount": monthly_amount,
        "yearly_amount": yearly_amount
    })

# Fetch customers and process data
@app.route('/customers', methods=['GET'])
def get_customers():
    conn = get_db_connection()
    cursor = conn.cursor(dictionary=True)

    cursor.execute("SELECT created_at FROM customer")
    customers = cursor.fetchall()
    conn.close()

    if not customers:
        return jsonify({"error": "No customer data found"}), 404

    # Convert to DataFrame
    df = pd.DataFrame(customers)
    df['created_at'] = pd.to_datetime(df['created_at'])

    today = datetime.now()
    daily = df[df['created_at'].dt.date == today.date()].shape[0]
    weekly = df[df['created_at'] >= today - timedelta(days=7)].shape[0]
    monthly = df[df['created_at'] >= today - timedelta(days=30)].shape[0]
    yearly = df[df['created_at'] >= today - timedelta(days=365)].shape[0]

    return jsonify({
        "daily_customers": daily,
        "weekly_customers": weekly,
        "monthly_customers": monthly,
        "yearly_customers": yearly
    })

if __name__ == '__main__':
    app.run(debug=True)
