# +A Simple PHP API to connect to database with web POST 
<br>
Params: "Db", "Pwd", "Uid", "Server"... In order to form connection with mysql
PostData: "Query", ["Replacements"], ["ResFormat"] :: [] = optional

Replacemnts => each array item replaces 1 "??" from query string

# +With a C# requester to that API (Wrapper)
<br>
Simple C# class the instantiates a web session and uses provided server, uid, pwd, and db to perform queries and return their results as a JObject (from Newtonsoft.Json).
--Note: Url is missing manual change is needed
