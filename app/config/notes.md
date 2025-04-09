<!-- 2ND BUILD/ITERATION-->
3.a) 
EX: PlayersService -> Helper class responsible for helper validation between model and controller (new comppnonent).

Client (POST, PUT, DELETE) -> WE CAN SEND DATA FROM CLIENTsend a request -> Controller (different callbacks for different methods ROUTE HANDLER/CALLBACK) -> IF DATA IS PRESENT (REQUEST INFO) ->  <- RESULT OBJ - Service (INPUT VALIDATION & GENERAL HELPER METHODS) -> IF THE INPUT IS VALID (DATA TO BE INSERTED, UPDATED AND DELETED) -> Model (IT WILL RETURN THE AFFECTED ID / ROWS) (NO VALIDATION HERE)

3.b) Result pattern needs to be used. It is a design used for runtime exceptions, to handle the outcome of methods (success or failure)
(FOR INVALID INPUTS)


You can pass multiple resources in the JSON content body in the thunder client.
we can use a for loop to handle multiple resources 
[{}, {}, ]

In the response, it should accept or not the response if inputs, etc are not valid/invalid.

FOR POST AND PUT, WE NEED VALIDATOR

        // TODO:

        BODY FROM THUNDERCLIENT

INSERT - LAST INSERTION ID
UPDATE - AFFECTED ROWS UPDATED
DELETED - AFFECTED ROWS DELETED

2nd assignment -> Another client 

2ND ITERATION
