math.randomseed(os.time())  -- Seed the random number generator with the current time


function generate_random_string(length)
    return sysbench.rand.string(string.rep('@', length))
end

function generate_random_email()
    local username = generate_random_string(sysbench.rand.uniform(5, 10))
    local domain = generate_random_string(sysbench.rand.uniform(5, 10))
    return username .. "@" .. domain .. ".com"
end

function generate_random_unix_time(start_date, end_date)
    local time_diff = end_date - start_date
    local random_days = math.random(0, time_diff)
    return start_date + random_days
end

function generate_random_date()
    local start_date = os.time({year = 1900, month = 1, day = 1})  -- Customize the start date
    local end_date = os.time({year = 2023, month = 12, day = 31})  -- Customize the end date
    local random_unix_time = generate_random_unix_time(start_date, end_date)
    return os.date("%Y-%m-%d", random_unix_time)
end

function prepare ()
    print("Creating table users ...")
    db_query(
            [[
            CREATE TABLE users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) NOT NULL,
                email VARCHAR(100) NOT NULL,
                birth_date DATE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
            ]])
end

function cleanup()
    print("Removing table users ...")
    db_query("drop table users")
end

function help()
    print("TODO")
end

function thread_init(thread_id)
end

function thread_done(thread_id)
    db_disconnect()
end

function event(thread_id)
    db_query(string.format(
            [[
                INSERT INTO users (username, email, birth_date)
                VALUES ('%s', '%s', '%s')
            ]],
            generate_random_string(sysbench.rand.uniform(5, 15)),
            generate_random_email(),
            generate_random_date()
    ))
end
