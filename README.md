# Sports Physio Clinic Appointment EDA Dashboard

<p>
  A full-stack web application for managing sports physiotherapy appointments, enriched with exploratory data analysis (EDA) and predictive modeling.
  Users can book, reschedule, and cancel appointments, while admins gain insights through charts and no-show risk predictions.
</p>

<hr />

<h2>🔧 Technologies Used</h2>
<ul>
  <li><strong>Frontend:</strong> HTML, Tailwind CSS, JavaScript</li>
  <li><strong>Backend:</strong> PHP, MySQL</li>
  <li><strong>Data Science:</strong> Python (<code>mysql-connector-python</code>, <code>pandas</code>, <code>matplotlib</code>, <code>seaborn</code>, <code>scikit-learn</code>)</li>
  <li><strong>Visualization:</strong> PNG charts generated via Python</li>
  <li><strong>Authentication:</strong> Basic login/signup for users and admins</li>
</ul>

<hr />

<h2>📁 Project Structure</h2>
<ul>
  <li><strong>HTML:</strong> <code>index.html</code>, <code>book.html</code>, <code>user_login.html</code>, <code>user_signup.html</code>, <code>admin_login.html</code></li>
  <li><strong>JavaScript:</strong> <code>validate.js</code></li>
  <li><strong>PHP:</strong> <code>db_connect.php</code>, <code>signup.php</code>, <code>user_login.php</code>, <code>admin_login.php</code>, <code>logout.php</code>,
    <code>book_appointment.php</code>, <code>user_dashboard.php</code>, <code>admin_dashboard.php</code>,
    <code>user_features.php</code>, <code>admin_features.php</code>, <code>view_user_appointments.php</code>, <code>delete_appointment.php</code>
  </li>
  <li><strong>Python:</strong>
    <ul>
      <li><code>clinic_eda.py</code> → generates 4 charts (<code>monthly_trends.png</code>, <code>service_popularity.png</code>, <code>peak_hours.png</code>, <code>cancellation_trends.png</code>)</li>
      <li><code>no_show_probability.py</code> → predicts cancellation risk using logistic regression</li>
    </ul>
  </li>
  <li><strong>SQL:</strong> <code>appointments.sql</code> → database schema and sample data</li>
  <li><strong>Images:</strong>
    <ul>
      <li>Static: <code>banner.jpg</code>, <code>injury.jpg</code>, <code>training.jpg</code>, <code>rehab.jpg</code></li>
      <li>Generated: 4 PNG charts from <code>clinic_eda.py</code></li>
    </ul>
  </li>
</ul>

<hr />

<h2>⚽ Basic Features of Sports Physio System</h2>

<h3>👤 User Features</h3>
<ul>
  <li>Sign up and login securely</li>
  <li>Book new appointments for physiotherapy services</li>
  <li>View upcoming and past appointments</li>
  <li>Reschedule appointments with date/time picker</li>
  <li>Cancel appointments directly from dashboard</li>
  <li>See appointment status (Pending, Confirmed, Cancelled)</li>
</ul>

<h3>🛠️ Admin Features</h3>
<ul>
  <li>Login with admin credentials</li>
  <li>View all user appointments</li>
  <li>Delete or manage appointments</li>
  <li>Access analytics dashboard with:
    <ul>
      <li>Monthly booking trends</li>
      <li>Service popularity</li>
      <li>Peak booking hours</li>
      <li>Cancellation patterns</li>
    </ul>
  </li>
  <li>See <strong>no-show risk predictions</strong> for each user (Low, Medium, High)</li>
</ul>

<hr />

<h2>📊 Admin Dashboard Features (EDA + ML)</h2>
<ul>
  <li>Appointment summary cards (Total, Pending, Confirmed, Cancelled)</li>
  <li>EDA charts:
    <ul>
      <li>Monthly booking trends</li>
      <li>Popular services</li>
      <li>Peak booking hours</li>
      <li>Cancellation patterns</li>
    </ul>
  </li>
  <li>No-show risk prediction:
    <ul>
      <li>Logistic regression model trained on user cancellation history</li>
      <li>Risk scores (Low, Medium, High) shown per user</li>
    </ul>
  </li>
</ul>

<hr />

<h2>🚀 How to Run</h2>

<h3>1. Database Setup</h3>
<ol>
  <li>Open <strong>XAMPP</strong> and start <strong>Apache</strong> + <strong>MySQL</strong>.</li>
  <li>Go to <strong>phpMyAdmin</strong> (<code>http://localhost/phpmyadmin</code>).</li>
  <li>Create a new database named <strong>phydb</strong>.</li>
  <li>Open the <strong>SQL</strong> tab of <strong>phydb</strong> and paste the contents of <code>appointments.sql</code>.</li>
  <li>Run the query to initialize tables and sample data.</li>
</ol>

<h3>2. Web Application</h3>
<ol>
  <li>Place project files in your XAMPP <code>htdocs</code> directory.</li>
  <li>Access:
    <ul>
      <li><code>index.html</code> → Landing page</li>
      <li><code>user_login.html</code> → User login</li>
      <li><code>admin_login.html</code> → Admin login</li>
      <li><code>user_dashboard.php</code> → User dashboard</li>
      <li><code>admin_dashboard.php</code> → Admin analytics view</li>
    </ul>
  </li>
</ol>

<h3>3. Data Analysis</h3>
<ol>
  <li>Run <code>clinic_eda.py</code> in terminal → generates 4 charts (<code>monthly_trends.png</code>, <code>service_popularity.png</code>, <code>peak_hours.png</code>, <code>cancellation_trends.png</code>).</li>
  <li>Run <code>no_show_probability.py</code> in terminal → prints risk scores and categories for each user.</li>
</ol>

<hr />

<h2>📌 Future Enhancements</h2>
<ul>
  <li>Add notifications</li>
  <li>Integrate calendar sync</li>
  <li>Improve ML model with more features (e.g., time gaps, service type)</li>
  <li>Add interactive chart filtering</li>
</ul>
