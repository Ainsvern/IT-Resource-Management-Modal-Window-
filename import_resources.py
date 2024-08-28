import pandas as pd
import mysql.connector

# Read the CSV file
df = pd.read_csv('resources.csv')

# Fill NaN values with an empty string
df.fillna('', inplace=True)

# Display the content of the CSV file
print("Content of the CSV file:")
print(df)

# Connect to the MySQL database
db = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="it_resource_management"
)

cursor = db.cursor()

# Insert the data into the 'resources' table
for index, row in df.iterrows():
    sql = """
    INSERT INTO resources (resource_name, type, specification, status, allocated_to, date_allocated)
    VALUES (%s, %s, %s, %s, %s, %s)
    """
    values = (
        row['Resource Name'], row['Type'], row['Specification'], row['Status'], row['Allocated To'], row['Date Allocated']
    )

    try:
        cursor.execute(sql, values)
    except mysql.connector.Error as err:
        print(f"Error: {err}")
        continue

# Commit the transaction
db.commit()

print("Data imported successfully")

# Fetch and display the current contents of the 'resources' table
cursor.execute("SELECT * FROM resources")
rows = cursor.fetchall()

print("\nCurrent contents of the 'resources' table:")
print("ID | Resource Name | Type | Specification | Status | Allocated To | Date Allocated")
print("=" * 100)

for row in rows:
    # Convert None to an empty string
    row = [str(item) if item is not None else '' for item in row]

    print(f"{row[0]:<5} {row[1]:<20} {row[2]:<15} {row[3]:<20} {row[4]:<15} {row[5]:<20} {row[6]:<15}")

# Close the cursor and connection
cursor.close()
db.close()