import pandas as pd
import mysql.connector
from sklearn.linear_model import LogisticRegression
from sklearn.model_selection import train_test_split

# 1. Connect to MySQL
conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",   # update if needed
    database="phydb"
)

# 2. Load appointment data
query = "SELECT username, status FROM appointments"
df = pd.read_sql(query, conn)
conn.close()

# 3. Feature engineering
user_stats = df.groupby("username")["status"].value_counts().unstack(fill_value=0)
user_stats["total"] = user_stats.sum(axis=1)
user_stats["Cancelled"] = user_stats.get("Cancelled", 0)
user_stats["cancel_ratio"] = user_stats["Cancelled"] / user_stats["total"]

# Features (X) and labels (y)
X = user_stats[["total", "Cancelled", "cancel_ratio"]].astype(float)
y = (user_stats["cancel_ratio"] > 0.2).astype(int)  # 1 = likely no-show

# 4. Check if we have both classes
if len(y.unique()) < 2:
    print("⚠️ Only one class found in labels. Falling back to heuristic scoring.")
    user_stats["prob_no_show"] = user_stats["cancel_ratio"]  # use ratio directly
else:
    # Train/test split
    X_train, X_test, y_train, y_test = train_test_split(
        X.fillna(0), y, test_size=0.2, random_state=42
    )

    # Train logistic regression
    model = LogisticRegression()
    model.fit(X_train, y_train)

    # Predict probabilities for all users
    user_stats["prob_no_show"] = model.predict_proba(X.fillna(0))[:, 1]

# 5. Assign risk category
def risk_category(prob):
    if prob > 0.7:
        return "High Risk"
    elif prob > 0.4:
        return "Medium Risk"
    else:
        return "Low Risk"

user_stats["risk"] = user_stats["prob_no_show"].apply(risk_category)

# 6. Show results
print(user_stats[["total", "Cancelled", "cancel_ratio", "prob_no_show", "risk"]])
