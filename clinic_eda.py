import pandas as pd
import seaborn as sns
import matplotlib.pyplot as plt
import mysql.connector

# 1. Connect to MySQL with your credentials
try:
    conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",        # empty if root has no password in XAMPP
    database="phydb"
)

    print("✅ Connection successful!")
except mysql.connector.Error as err:
    print(f"❌ Error: {err}")
    exit()

# 2. Load data into Pandas
query = "SELECT * FROM appointments"
df = pd.read_sql(query, conn)

# 3. Service Popularity
plt.figure(figsize=(6,4))
sns.countplot(data=df, x="service")
plt.title("Service Popularity")
plt.xlabel("Service Type")  # Optional: cleaner label
plt.ylabel("Count")
plt.xticks(rotation=0)      # 🔧 Set rotation to 0 for horizontal labels
plt.tight_layout()
plt.savefig("service_popularity.png")


# 4. Peak Hours
# Convert "0 days 18:45:00" → "18:45:00" → hour = 18
df['hour'] = df['appointment_time'].astype(str).str.extract(r'(\d{2}):')[0].astype(int)

plt.figure(figsize=(6,4))
sns.histplot(df['hour'], bins=12, color="skyblue")
plt.title("Peak Appointment Hours")
plt.xlabel("Hour of Day")
plt.tight_layout()
plt.savefig("peak_hours.png")



# 5. Monthly Trends
df['month'] = pd.to_datetime(df['appointment_date']).dt.month
plt.figure(figsize=(6,4))
sns.countplot(data=df, x="month", color="green" , width=0.35)
plt.title("Monthly Appointment Trends")
plt.xlabel("Month")
plt.tight_layout()
plt.savefig("monthly_trends.png")

# 6. Cancellation Trends
plt.figure(figsize=(6,4))
sns.countplot(data=df, x="status", palette="Set2")
plt.title("Cancellation vs Confirmation")
plt.tight_layout()
plt.savefig("cancellation_trends.png")

print("📊 EDA charts generated successfully!")
