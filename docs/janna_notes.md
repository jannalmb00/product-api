TODO:
create a class that is responsible for input validator
organize validator in a separate class
result pattern - alternative for runtime exception

client[POST, PUT, DELETE] -> controller (route handler/ callback)---requet info---> service (input validation/ any general/helper) ---- data to  be inserted/deleted--> model (no validation here)

model ---> return depending on the operation --->  service
to know the returns, check the documentation of teh base model
[POST, PUT, DELETE] must use separate callbacks


