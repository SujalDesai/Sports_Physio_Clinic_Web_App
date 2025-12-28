import pandas as pd
import seaborn as sns
import matplotlib.pyplot as plt
import mysql.connector



try:
    conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",       
    database="phydb"
)
    print("✅ Connection successful!")
    
except mysql.connector.Error as err:
    print(f"❌ Error: {err}")
    exit()

query = "SELECT * FROM appointments"
df = pd.read_sql(query, conn)

# Service Popularity
plt.figure(figsize=(6,4))
sns.countplot(data=df, x="service")
plt.title("Service Popularity")
plt.xlabel("Service Type")  
plt.ylabel("Count")
plt.xticks(rotation=0)     
plt.tight_layout()
plt.savefig("service_popularity.png")


# Peak Hours
df['hour'] = df['appointment_time'].astype(str).str.extract(r'(\d{2}):')[0].astype(int)

plt.figure(figsize=(6,4))
sns.histplot(df['hour'], bins=12, color="skyblue")
plt.title("Peak Appointment Hours")
plt.xlabel("Hour of Day")
plt.tight_layout()
plt.savefig("peak_hours.png")



# Monthly Trends
df['month'] = pd.to_datetime(df['appointment_date']).dt.month
plt.figure(figsize=(6,4))
sns.countplot(data=df, x="month", color="green" , width=0.35)
plt.title("Monthly Appointment Trends")
plt.xlabel("Month")
plt.tight_layout()
plt.savefig("monthly_trends.png")

# Cancellation Trends
plt.figure(figsize=(6,4))
sns.countplot(data=df, x="status", palette="Set2")
plt.title("Cancellation vs Confirmation")
plt.tight_layout()
plt.savefig("cancellation_trends.png")

print("📊 EDA charts generated successfully!")

