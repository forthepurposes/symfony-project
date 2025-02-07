## Symfony Project

**It has but not limited to some main key-features:**

# Caching System 
It uses Redis for inter-server communication and shares data for a period of time to keep track of it and avoid going to database asking for the same query all the time

# Grafana/InfluxDB
It has expendable metric system which can keep track of litreally anything what can come to mind. Currently it keeps track of application's memory and cpu usages, in the future the list of metrices can be extended. It uses InfluxDB as time series database to store metric data to later display it in Grafana.

Example
![image](https://github.com/user-attachments/assets/efab0992-df45-4b90-856a-8bc830ea6fa5)

# Blog System
A simple rest controller which is utilizing abilities of the Caching system to quickly retrieve data to display available blogs. It also has an ability to create new blogs 
