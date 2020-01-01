import java.sql.*;
import java.util.Scanner;

public class cs353 {
	static Scanner scan = new Scanner(System.in);
	
	public static void main(String[] args) {
		Connection con = null;
		
		try {
			//load JBDC driver
			Class.forName("com.mysql.jdbc.Driver"); 
			System.out.println("Driver Loaded");
			
			//connect to the DB
			con = connect(con);
			
			//drop the tables if they are already existing
			drop(con);
			
			//create tables
			createTables(con);
			
			//insert user tuples
			//type = 1: customer, 2: tech staff, 3: customer service
			insertToUser(con, "1001", "Tarik", "qwerty", "Ankara", "05383715609", "1");
			insertToUser(con, "1002", "Cemil", "qwerty", "Ankara", "05555555555", "1");
			insertToUser(con, "2001", "Sarp", "qwerty", "Ankara", "05656566556", "2");
			insertToUser(con, "2002", "Ekin", "qwerty", "Ankara", "05757575757", "2");
			insertToUser(con, "2003", "Ahmet", "qwerty", "Ankara", "0525252525", "2");
			insertToUser(con, "3001", "Mehmet", "qwerty", "Ankara", "0595959595", "3");
			
			//insert customer tuples
			insertToCustomer(con, "1001", "29/08/1998" );
			insertToCustomer(con, "1002", "18/07/1998" );
			
			//insert employee tuples
			insertToEmployee(con, "2001", "30000", "9:00-17:00");
			insertToEmployee(con, "2002", "20000", "9:00-17:00");
			insertToEmployee(con, "2003", "40000", "8:00-16:00");
			insertToEmployee(con, "3001", "18000", "9:00-17:00");
			
			//insert tech staff tuples
			insertToTechStaff(con, "2001", "Fridge Repair");
			insertToTechStaff(con, "2002", "Fridge Maintenance");
			insertToTechStaff(con, "2003", "Computer Repair");
			
			//insert customer service tuples
			insertToCustomerService(con, "3001", "Customer Service Manager");
			
			//insert product tuples
			insertToProduct(con, "4001", "Lenovo Ideapad 330 Laptop");
			insertToProduct(con, "4002", "Monster Gaming Laptop");
			insertToProduct(con, "4003", "Arcelik Fridge");
			insertToProduct(con, "4004", "Bosch Fridge");
			
			//insert buys tuples
			insertToBuys(con, "1001", "4001");
			insertToBuys(con, "1001", "4002");
			insertToBuys(con, "1001", "4003");
			insertToBuys(con, "1002", "4001");
			
			//insert category tuples
			insertToCategory(con, "Household Appliances");
			insertToCategory(con, "Computer");
			insertToCategory(con, "Laptop");
			insertToCategory(con, "Desktop");
			insertToCategory(con, "Fridge");
			
			//insert belong tuples
			insertToBelongs(con, "Laptop", "4001" );
			insertToBelongs(con, "Laptop", "4002" );
			insertToBelongs(con, "Fridge", "4003" );
			insertToBelongs(con, "Fridge", "4004" );
			
			//insert subcategory tuples
			insertToSubcategory(con, "Household Appliances", "Fridge");
			insertToSubcategory(con, "Computer", "Desktop");
			insertToSubcategory(con, "Computer", "Laptop");
			
			//insert repair request tuples
			insertToRepairRequest(con, "5001", "1001", "2003", "4001", "Ongoing", "My screen is broken.", "Waiting for repairment");
			
			//insert repair tuples
			insertToRepair(con, "5001", "2003", "Screen cannot be fixed, new screen is needed.", null);
			
			//insert spare part tuples
			insertToSparePart(con, "6001", "Lenovo Screen 17 inches", "200");
			insertToSparePart(con, "6002", "Monster CPU Unit", "100");
			insertToSparePart(con, "6003", "Arcelik Cooler Unit", "50");
			
			//insert request tuples
			insertToRequest(con, "5001", "2003", "6001");
			
			//insert complaint tuples
			insertToComplaint(con, "7001", "5001", "1001", "3001", "Repair request taking too long", "I made a repair request and I approved the repairment, it has been 2 weeks but I haven\\'t been notified of the process. What\\'s taking so long?");
			
			//insert conversation tuples
			insertToConversation(con, "7001", "1", "1001", "3001", "I demand to be informed of my product\\'s repairment process.", "28/12/2019", "The spare part needed for your product arrived only yesterday, your product\\'s repairment process has begun today.", "29/12/2019");
			insertToConversation(con, "7001", "2", "1001", "3001", "Yet you only inform me about this today. I won\\'t buy from you again.", "28/12/2019", null, null);
		}
		catch(ClassNotFoundException e) { //exception for JBDC driver
			throw new IllegalStateException("Cannot find the driver", e);
		}
		finally { //closing
			try {
				if(con!=null)
					con.close();
		    }catch( SQLException e ) {
		    	throw new IllegalStateException("Cannot close the connection", e);
		    }
		}
	}
	
	public static Connection connect( Connection con) {
		while(true) {
			//Creating DB URL
			String username = "emin.kaplan";
			String password = "mfhAGKtw";
			String usernameForURL = username.replace('.', '_');
			String url = "jdbc:mysql://dijkstra.ug.bcc.bilkent.edu.tr:3306/" + usernameForURL;
			try {
				System.out.println("Connecting.....");
				con = DriverManager.getConnection( url, username, password );
				System.out.println("Connected......");
				return con;
			}
			catch( SQLException e ) { //exception for connection
				System.out.println("Cannot connect to the database, please check your credentials");
			}
		}
	}
	
	public static void createTables( Connection con ) {
		try {
			System.out.println("Creating the tables");
		    Statement stmt = con.createStatement();
		    //SQL strings to create tables.
		    String userSql = "CREATE TABLE user(" +
	                   " id INT not NULL AUTO_INCREMENT, " +
	                   " name VARCHAR(45) not NULL, " + 
	                   " password VARCHAR(45) not NULL, " +
	                   " adress VARCHAR(45) not NULL, " + 
	                   " phone_number VARCHAR(45) not NULL, " +
	                   " type INT(1) not NULL, " + 
	                   " PRIMARY KEY ( id ) " +
	                   " ) ENGINE=INNODB";
		    String customerSql = "CREATE TABLE customer(" +
	                   " id INT not NULL, " +
	                   " birthday VARCHAR(45) not NULL, " + 
	                   " PRIMARY KEY ( id ), " +
	                   " FOREIGN KEY (id) REFERENCES user(id) " +
	                   " ) ENGINE=INNODB";
		    String employeeSql = "CREATE TABLE employee(" +
	                   " id INT not NULL, " +
	                   " salary INT not NULL, " +
	                   " work_hours VARCHAR(45) not NULL, " + 
	                   " PRIMARY KEY ( id ), " +
	                   " FOREIGN KEY (id) REFERENCES user(id) " +
	                   " ) ENGINE=INNODB";
		    String techStaffSql = "CREATE TABLE tech_staff(" +
	                   " id INT not NULL, " +
	                   " expertise VARCHAR(45) not NULL, " + 
	                   " PRIMARY KEY ( id ), " +
	                   " FOREIGN KEY (id) REFERENCES employee(id) " +
	                   " ) ENGINE=INNODB";
		    String customerServiceSql = "CREATE TABLE customer_service(" +
	                   " id INT not NULL, " +
	                   " position VARCHAR(45) not NULL, " + 
	                   " PRIMARY KEY ( id ), " +
	                   " FOREIGN KEY (id) REFERENCES employee(id) " +
	                   " ) ENGINE=INNODB";
		    String productSql = "CREATE TABLE product(" +
	                   " id INT not NULL AUTO_INCREMENT, " +
	                   " name VARCHAR(45) not NULL, " + 
	                   " PRIMARY KEY ( id ) " +
	                   " ) ENGINE=INNODB";
		    String buysSql = "CREATE TABLE buys(" +
	                   " customer_id INT not NULL, " +
	                   " product_id INT not NULL, " +
	                   " PRIMARY KEY ( customer_id, product_id ), " +
	                   " FOREIGN KEY (customer_id) REFERENCES customer(id), " +
	                   " FOREIGN KEY (product_id) REFERENCES product(id) " +
	                   " ) ENGINE=INNODB";
		    String categorySql = "CREATE TABLE category(" +
		    		   " name VARCHAR(45) not NULL, " +
	                   " PRIMARY KEY ( name ) " +
	                   " ) ENGINE=INNODB";			    
		    String belongsSql = "CREATE TABLE belongs(" +
		    		   " category_name VARCHAR(45) not NULL, " +
		    		   " product_id INT not NULL, " +
	                   " PRIMARY KEY ( category_name, product_id ), " +
	                   " FOREIGN KEY (category_name) REFERENCES category(name), " +
	                   " FOREIGN KEY (product_id) REFERENCES product(id) " +
	                   " ) ENGINE=INNODB";
		    String subcategorySql = "CREATE TABLE subcategory(" +
		    		   " category_name VARCHAR(45) not NULL, " +
		    		   " subcategory_name VARCHAR(45) not NULL, " +
	                   " PRIMARY KEY ( category_name, subcategory_name ), " +
	                   " FOREIGN KEY (category_name) REFERENCES category(name), " +
	                   " FOREIGN KEY (subcategory_name) REFERENCES category(name) " +
	                   " ) ENGINE=INNODB";
		    String repairRequestSql = "CREATE TABLE repair_request(" +
		    		   " repair_request_id INT not NULL AUTO_INCREMENT, " +
		    		   " customer_id INT not NULL, " +
		    		   " tech_staff_id INT not NULL, " +
	                   " product_id INT not NULL, " +
	                   " status VARCHAR(45) not NULL, " +
	                   " explanation MEDIUMTEXT not NULL, " +
	                   " decision VARCHAR(45), " +
	                   " PRIMARY KEY ( repair_request_id ), " +
	                   " FOREIGN KEY (customer_id) REFERENCES customer(id), " +
	                   " FOREIGN KEY (tech_staff_id) REFERENCES tech_staff(id), " +
	                   " FOREIGN KEY (product_id) REFERENCES product(id) " +
	                   " ) ENGINE=INNODB";
		    String repairSql = "CREATE TABLE repair(" +
		    		   " repair_request_id INT not NULL, " +
		    		   " tech_staff_id INT not NULL, " +
		    		   " preliminary_report MEDIUMTEXT not NULL, " +
		    		   " detailed_report MEDIUMTEXT, " +
	                   " PRIMARY KEY ( repair_request_id, tech_staff_id ), " +
	                   " FOREIGN KEY (repair_request_id) REFERENCES repair_request(repair_request_id), " +
	                   " FOREIGN KEY (tech_staff_id) REFERENCES tech_staff(id) " +
	                   " ) ENGINE=INNODB";
		    String sparePartSql = "CREATE TABLE spare_part(" +
	                   " id INT not NULL AUTO_INCREMENT, " +
	                   " name VARCHAR(45) not NULL, " + 
	                   " quantity INT not NULL, " + 
	                   " PRIMARY KEY ( id ) " +
	                   " ) ENGINE=INNODB";
		    String requestSql = "CREATE TABLE request(" +
		    		   " repair_request_id INT not NULL, " +
		    		   " tech_staff_id INT not NULL, " + 
		    		   " spare_part_id INT not NULL, " +
	                   " PRIMARY KEY ( repair_request_id, tech_staff_id, spare_part_id ), " +
	                   " FOREIGN KEY (repair_request_id) REFERENCES repair_request(repair_request_id), " +
	                   " FOREIGN KEY (tech_staff_id) REFERENCES tech_staff(id), " +
	                   " FOREIGN KEY (spare_part_id) REFERENCES spare_part(id) " +
	                   " ) ENGINE=INNODB";
		    String complaintSql = "CREATE TABLE complaint(" +
		    		   " complaint_id INT not NULL AUTO_INCREMENT, " +
		    		   " repair_request_id INT not NULL, " + 
		    		   " customer_id INT not NULL, " + 
		    		   " customer_service_id INT not NULL, " +
		    		   " topic VARCHAR(45) not NULL, " + 
		    		   " explanation MEDIUMTEXT not NULL, " +
	                   " PRIMARY KEY ( complaint_id ), " +
	                   " FOREIGN KEY (repair_request_id) REFERENCES repair_request(repair_request_id), " +
	                   " FOREIGN KEY (customer_id) REFERENCES customer(id), " +
	                   " FOREIGN KEY (customer_service_id) REFERENCES customer_service(id) " +
	                   " ) ENGINE=INNODB";
		    String conversationSql = "CREATE TABLE conversation(" +
		    		   " complaint_id INT not NULL, " +   
		    		   " conversation_no INT(5) not NULL, " +
		    		   " customer_id INT not NULL, " +
		    		   " customer_service_id INT not NULL, " +
		    		   " customer_msg TINYTEXT, " +
		    		   " customer_msg_date VARCHAR(45), " +
		    		   " customer_service_msg TINYTEXT, " +
		    		   " customer_service_msg_date VARCHAR(45), " +
	                   " PRIMARY KEY ( complaint_id, conversation_no ), " +
	                   " FOREIGN KEY (complaint_id) REFERENCES complaint(complaint_id), " +
	                   " FOREIGN KEY (customer_id) REFERENCES customer(id), " +
	                   " FOREIGN KEY (customer_service_id) REFERENCES customer_service(id) " +
	                   " ) ENGINE=INNODB";
		    
		    stmt.executeUpdate(userSql);
		    stmt.executeUpdate(customerSql);
		    stmt.executeUpdate(employeeSql);
		    stmt.executeUpdate(techStaffSql);
		    stmt.executeUpdate(customerServiceSql);
		    stmt.executeUpdate(productSql);
		    stmt.executeUpdate(buysSql);
		    stmt.executeUpdate(categorySql);
		    stmt.executeUpdate(belongsSql);
		    stmt.executeUpdate(subcategorySql);
		    stmt.executeUpdate(repairRequestSql);
		    stmt.executeUpdate(repairSql);
		    stmt.executeUpdate(sparePartSql);
		    stmt.executeUpdate(requestSql);
		    stmt.executeUpdate(complaintSql);
		    stmt.executeUpdate(conversationSql);
		    System.out.println("Created the tables");
		}
		catch( SQLException e ) {
			throw new IllegalStateException("Cannot create tables", e);
		}
	}
	
	public static void drop( Connection con ) {
		try {
			System.out.println("Dropping the existing tables");
		    Statement stmt = con.createStatement();
		    
		    //SQL strings to drop tables
		    String userSql = "DROP TABLE IF EXISTS user";
		    String customerSql = "DROP TABLE IF EXISTS customer";
		    String employeeSql = "DROP TABLE IF EXISTS employee";
		    String techStaffSql = "DROP TABLE IF EXISTS tech_staff";
		    String customerServiceSql = "DROP TABLE IF EXISTS customer_service";
		    String productSql = "DROP TABLE IF EXISTS product";
		    String buysSql = "DROP TABLE IF EXISTS buys";
		    String categorySql = "DROP TABLE IF EXISTS category";
		    String belongsSql = "DROP TABLE IF EXISTS belongs";
		    String subcategorySql = "DROP TABLE IF EXISTS subcategory";
		    String repairRequestSql = "DROP TABLE IF EXISTS repair_request";
		    String repairSql = "DROP TABLE IF EXISTS repair";
		    String sparePartSql = "DROP TABLE IF EXISTS spare_part";
		    String requestSql = "DROP TABLE IF EXISTS request";
		    String complaintSql = "DROP TABLE IF EXISTS complaint";
		    String conversationSql = "DROP TABLE IF EXISTS conversation";
		    
		    
		    stmt.executeUpdate(conversationSql);
		    stmt.executeUpdate(complaintSql);
		    stmt.executeUpdate(requestSql);
		    stmt.executeUpdate(repairSql);
		    
		    stmt.executeUpdate(belongsSql);
		    stmt.executeUpdate(subcategorySql);
		    stmt.executeUpdate(buysSql);
		    
		    stmt.executeUpdate(categorySql);
		    stmt.executeUpdate(repairRequestSql);
		    stmt.executeUpdate(sparePartSql);
		    stmt.executeUpdate(productSql);
		    stmt.executeUpdate(customerServiceSql);
		    stmt.executeUpdate(techStaffSql);
		    stmt.executeUpdate(employeeSql);
		    stmt.executeUpdate(customerSql);
		    stmt.executeUpdate(userSql);
		    
		    System.out.println("Dropped the existing tables");
		}
		catch( SQLException e ) {
			throw new IllegalStateException("Cannot drop tables", e);
		}
	}
	
	public static void insertToUser( Connection con, String id, String name, String password, String adress, String phone_number, String type ) {
		try {
			System.out.println("Inserting " + id + " into user");
		    Statement stmt = con.createStatement();
		    //SQL string to insert
		    String sql = "INSERT INTO user (id, name, password, adress, phone_number, type) VALUES (" +
		    			 "'" + id + "'," +
		    			 "'" + name + "'," +
		    			 "'" + password + "'," +
		    			 "'" + adress + "'," +
		    			 "'" + phone_number + "'," +
		    			 "'" + type + "'" +
		    			 " )";
		    
		    stmt.executeUpdate(sql);
		    System.out.println("Inserted " + id + " into user");
		}
		catch( SQLException e ) {
			throw new IllegalStateException("Cannot insert " + id + " into user: ", e);
		}
	}
	
	public static void insertToCustomer( Connection con, String id, String birthday ) {
		try {
			System.out.println("Inserting " + id + " into customer");
		    Statement stmt = con.createStatement();
		    //SQL string to insert
		    String sql = "INSERT INTO customer (id, birthday) VALUES (" +
		    			 "'" + id + "'," +
		    			 "'" + birthday + "'" +
		    			 " )";
		    
		    stmt.executeUpdate(sql);
		    System.out.println("Inserted " + id + " into customer");
		}
		catch( SQLException e ) {
			throw new IllegalStateException("Cannot insert " + id + " into customer: ", e);
		}
	}
	
	public static void insertToEmployee( Connection con, String id, String salary, String work_hours ) {
		try {
			System.out.println("Inserting " + id + " into employee");
		    Statement stmt = con.createStatement();
		    //SQL string to insert
		    String sql = "INSERT INTO employee (id, salary, work_hours) VALUES (" +
		    			 "'" + id + "'," +
		    			 "'" + salary + "'," +
		    			 "'" + work_hours + "'" +
		    			 " )";
		    
		    stmt.executeUpdate(sql);
		    System.out.println("Inserted " + id + " into employee");
		}
		catch( SQLException e ) {
			throw new IllegalStateException("Cannot insert " + id + " into employee: ", e);
		}
	}
	
	public static void insertToTechStaff( Connection con, String id, String expertise ) {
		try {
			System.out.println("Inserting " + id + " into tech_staff");
		    Statement stmt = con.createStatement();
		    //SQL string to insert
		    String sql = "INSERT INTO tech_staff (id, expertise) VALUES (" +
		    			 "'" + id + "'," +
		    			 "'" + expertise + "'" +
		    			 " )";
		    
		    stmt.executeUpdate(sql);
		    System.out.println("Inserted " + id + " into tech_staff");
		}
		catch( SQLException e ) {
			throw new IllegalStateException("Cannot insert " + id + " into tech_staff: ", e);
		}
	}

	public static void insertToCustomerService( Connection con, String id, String position ) {
		try {
			System.out.println("Inserting " + id + " into customer_service");
		    Statement stmt = con.createStatement();
		    //SQL string to insert
		    String sql = "INSERT INTO customer_service (id, position) VALUES (" +
		    			 "'" + id + "'," +
		    			 "'" + position + "'" +
		    			 " )";
		    
		    stmt.executeUpdate(sql);
		    System.out.println("Inserted " + id + " into customer_service");
		}
		catch( SQLException e ) {
			throw new IllegalStateException("Cannot insert " + id + " into customer_service: ", e);
		}
	}
	
	public static void insertToProduct( Connection con, String id, String name ) {
		try {
			System.out.println("Inserting " + id + " into product");
		    Statement stmt = con.createStatement();
		    //SQL string to insert
		    String sql = "INSERT INTO product (id, name) VALUES (" +
		    			 "'" + id + "'," +
		    			 "'" + name + "'" +
		    			 " )";
		    
		    stmt.executeUpdate(sql);
		    System.out.println("Inserted " + id + " into product");
		}
		catch( SQLException e ) {
			throw new IllegalStateException("Cannot insert " + id + " into product: ", e);
		}
	}
	
	public static void insertToBuys( Connection con, String customer_id, String product_id ) {
		try {
			System.out.println("Inserting " + customer_id + ", " + product_id + " into buys");
		    Statement stmt = con.createStatement();
		    //SQL string to insert
		    String sql = "INSERT INTO buys (customer_id, product_id) VALUES (" +
		    			 "'" + customer_id + "'," +
		    			 "'" + product_id + "'" +
		    			 " )";
		    
		    stmt.executeUpdate(sql);
		    System.out.println("Inserted " + customer_id + ", " + product_id + " into buys");
		}
		catch( SQLException e ) {
			throw new IllegalStateException("Cannot insert " + customer_id + ", " + product_id + " into buys: ", e);
		}
	}
	
	public static void insertToCategory( Connection con, String name ) {
		try {
			System.out.println("Inserting " + name + " into category");
		    Statement stmt = con.createStatement();
		    //SQL string to insert
		    String sql = "INSERT INTO category (name) VALUES (" +
		    			 "'" + name + "'" +
		    			 " )";
		    
		    stmt.executeUpdate(sql);
		    System.out.println("Inserted " + name + " into category");
		}
		catch( SQLException e ) {
			throw new IllegalStateException("Cannot insert " + name + " into category: ", e);
		}
	}
	
	public static void insertToBelongs( Connection con, String category_name, String product_id ) {
		try {
			System.out.println("Inserting " + category_name + ", " + product_id + " into belongs");
		    Statement stmt = con.createStatement();
		    //SQL string to insert
		    String sql = "INSERT INTO belongs (category_name, product_id) VALUES (" +
		    			 "'" + category_name + "'," +
		    			 "'" + product_id + "'" +
		    			 " )";
		    
		    stmt.executeUpdate(sql);
		    System.out.println("Inserted " + category_name + ", " + product_id + " into belongs");
		}
		catch( SQLException e ) {
			throw new IllegalStateException("Cannot insert " + category_name + ", " + product_id + " into belongs: ", e);
		}
	}
		
	public static void insertToSubcategory( Connection con, String category_name, String subcategory_name ) {
		try {
			System.out.println("Inserting " + category_name + ", " + subcategory_name + " into subcategory");
		    Statement stmt = con.createStatement();
		    //SQL string to insert
		    String sql = "INSERT INTO subcategory (category_name, subcategory_name) VALUES (" +
		    			 "'" + category_name + "'," +
		    			 "'" + subcategory_name + "'" +
		    			 " )";
		    
		    stmt.executeUpdate(sql);
		    System.out.println("Inserted " + category_name + ", " + subcategory_name + " into subcategory");
		}
		catch( SQLException e ) {
			throw new IllegalStateException("Cannot insert " + category_name + ", " + subcategory_name + " into subcategory: ", e);
		}
	}
	
	public static void insertToRepairRequest( Connection con, String repair_request_id, String customer_id, String tech_staff_id, String product_id, String status, String explanation, String decision) {
		try {
			System.out.println("Inserting " + repair_request_id + " into repair_request");
		    Statement stmt = con.createStatement();
		    //SQL string to insert
		    String sql = "INSERT INTO repair_request (repair_request_id, customer_id, tech_staff_id, product_id, status, explanation, decision) VALUES (" +
		    			 "'" + repair_request_id + "'," +
		    			 "'" + customer_id + "'," +
		    			 "'" + tech_staff_id + "'," +
		    			 "'" + product_id + "'," +
		    			 "'" + status + "'," +
		    			 "'" + explanation + "'," +
		    			 "'" + decision + "'" +
		    			 " )";
		    
		    stmt.executeUpdate(sql);
		    System.out.println("Inserted " + repair_request_id + " into repair_request");
		}
		catch( SQLException e ) {
			throw new IllegalStateException("Cannot insert " + repair_request_id + " into repair_request: ", e);
		}
	}
	
	public static void insertToRepair( Connection con, String repair_request_id, String tech_staff_id, String preliminary_report, String detailed_report ) {
		try {
			System.out.println("Inserting " + repair_request_id + ", " + tech_staff_id + " into repair");
		    Statement stmt = con.createStatement();
		    //SQL string to insert
		    String sql = "INSERT INTO repair (repair_request_id, tech_staff_id, preliminary_report, detailed_report) VALUES (" +
		    			 "'" + repair_request_id + "'," +
		    			 "'" + tech_staff_id + "'," +
		    			 "'" + preliminary_report + "'," +
		    			 "'" + detailed_report + "'" +
		    			 " )";
		    
		    stmt.executeUpdate(sql);
		    System.out.println("Inserted " + repair_request_id + ", " + tech_staff_id + " into repair");
		}
		catch( SQLException e ) {
			throw new IllegalStateException("Cannot insert " + repair_request_id + ", " + tech_staff_id + " into repair: ", e);
		}
	}
	
	public static void insertToSparePart( Connection con, String id, String name, String quantity ) {
		try {
			System.out.println("Inserting " + id + " into spare_part");
		    Statement stmt = con.createStatement();
		    //SQL string to insert
		    String sql = "INSERT INTO spare_part (id, name, quantity) VALUES (" +
		    			 "'" + id + "'," +
		    			 "'" + name + "'," +
		    			 "'" + quantity + "'" +
		    			 " )";
		    
		    stmt.executeUpdate(sql);
		    System.out.println("Inserted " + id + " into spare_part");
		}
		catch( SQLException e ) {
			throw new IllegalStateException("Cannot insert " + id + " into spare_part: ", e);
		}
	}
	
	public static void insertToRequest( Connection con, String repair_request_id, String tech_staff_id, String spare_part_id ) {
		try {
			System.out.println("Inserting " + repair_request_id + ", " + tech_staff_id + ", " + spare_part_id + " into request");
		    Statement stmt = con.createStatement();
		    //SQL string to insert
		    String sql = "INSERT INTO request (repair_request_id, tech_staff_id, spare_part_id) VALUES (" +
		    			 "'" + repair_request_id + "'," +
		    			 "'" + tech_staff_id + "'," +
		    			 "'" + spare_part_id + "'" +
		    			 " )";
		    
		    stmt.executeUpdate(sql);
		    System.out.println("Inserted " + repair_request_id + ", " + tech_staff_id + ", " + spare_part_id + " into request");
		}
		catch( SQLException e ) {
			throw new IllegalStateException("Cannot insert " + repair_request_id + ", " + tech_staff_id + ", " + spare_part_id + " into request: ", e);
		}
	}
	
	public static void insertToComplaint( Connection con, String complaint_id, String repair_request_id, String customer_id, String customer_service_id, String topic, String explanation ) {
		try {
			System.out.println("Inserting " + complaint_id + " into complaint");
		    Statement stmt = con.createStatement();
		    //SQL string to insert
		    String sql = "INSERT INTO complaint (complaint_id, repair_request_id, customer_id, customer_service_id, topic, explanation) VALUES (" +
		    			 "'" + complaint_id + "'," +
		    			 "'" + repair_request_id + "'," +
		    			 "'" + customer_id + "'," +
		    			 "'" + customer_service_id + "'," +
		    			 "'" + topic + "'," +
		    			 "'" + explanation + "'" +
		    			 " )";
		    
		    stmt.executeUpdate(sql);
		    System.out.println("Inserted " + complaint_id + " into complaint");
		}
		catch( SQLException e ) {
			throw new IllegalStateException("Cannot insert " + complaint_id + " into complaint: ", e);
		}
	}
	
	public static void insertToConversation( Connection con, String complaint_id, String conversation_no, String customer_id, String customer_service_id, String customer_msg, String customer_msg_date, String customer_service_msg, String customer_service_msg_date ) {
		try {
			System.out.println("Inserting " + complaint_id + ", " + conversation_no + " into conversation");
		    Statement stmt = con.createStatement();
		    //SQL string to insert
		    String sql = "INSERT INTO conversation (complaint_id, conversation_no, customer_id, customer_service_id, customer_msg, customer_msg_date, customer_service_msg, customer_service_msg_date ) VALUES (" +
		    			 "'" + complaint_id + "'," +
		    			 "'" + conversation_no + "'," +
		    			 "'" + customer_id + "'," +
		    			 "'" + customer_service_id + "'," +
		    			 "'" + customer_msg + "'," +
		    			 "'" + customer_msg_date + "'," +
		    			 "'" + customer_service_msg + "'," +
		    			 "'" + customer_service_msg_date + "'" +
		    			 " )";
		    
		    stmt.executeUpdate(sql);
		    System.out.println("Inserted " + complaint_id + ", " + conversation_no + " into conversation");
		}
		catch( SQLException e ) {
			throw new IllegalStateException("Cannot insert " + complaint_id + ", " + conversation_no + " into conversation: ", e);
		}
	}
}
