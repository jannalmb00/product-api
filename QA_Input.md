##  Examples of Inputs for /products

## GET /products

> Retrieves a list of products. Results vary depending on filters and sorting specified. 

### Pagination

> Pagination uses page and page_size query parameters
> If page and page_size is not specified, the default values are 1 and 10 respectively

### Filtering

> Fields that can be filtered: product_name, product_origin, brand_name, category_name

> Query format: ?product_origin=Canada&product_name=Peach Passion Energy Infusion&brand_name=Mateina&category_name=Energy Drink

> Data types of each filtering parameter:
> product_name = string
> product_origin = string
> brand_name = string
> category_name = string

### Sorting 

> Sorts the list either by ascending or descending and can be ordered by using specific fields.
> Approved ordering filters: product_name, product_origin, brand_name, category_name
> Sorting parameters: sort and ordered_by
> Sorted ascending by default
> Ordered by product_id by default   
> Query format: ?sort=asc&order_by=product_name


## CORRECT Inputs

product_origin: Canada
product_name: Peach Passion Energy Infusion
brand_name: Matiena
category_name: Energy Drink
sort: ascending
order_by: product_name

## INCORRECT Inputs

product_origin: Brazil
product_name: Passion Energy Infusion
brand_name: ,kjn90
category_name: beverage



### GET /products/{product_id}

> Retrieves a product based on the product id

### Pagination

> Pagination uses page and page_size query parameters
> If page and page_size is not specified, the default values are 1 and 10, respectively

## CORRECT Inputs

page: 1 
page_size: 1
product_id: P00002

## INCORRECT Inputs

page: 1 
page_size: 1
product_id: P000020


### GET /products/{product_id}/nutrition

> Retrieves nutrition information based on product id

### Pagination 
> Pagination uses page and page_size query parameters
> If page and page_size is not specified, the default values are 1 and 10 respectively


## CORRECT Inputs

page: 2 
page_size: 10
Product_id: P00001 

## INCORRECT Inputs

page: 2 
page_size: 10
Product_id: P001 



### POST /products

## CORRECT Inputs

> Product_id is required

```json
[
  {
    "product_id": "P00010",
    "product_name": "Blueberry Passion Energy Infusion",
    "product_barcode": "3147483647",
    "product_origin": "Canada",
    "product_serving_size": 355,
    "product_image": "https://mateina.ca/cdn/shop/files/Mateina-Organic-…",
    "nutrition_id": "N00001",
    "diet_id": "DA0001",
    "brand_id": "B00001",
    "category_id": "C-0004",
    "environmental_id": "E00001"
  }
]
```

```json
[
  {
    "product_id": "P00011",
    "product_name": "Soy Drink Barista Edition",
    "product_barcode": "4147483647",
    "product_origin": "Sweden",
    "product_serving_size": 240,
    "product_image": "https://m.media-amazon.com/images/I/51eUlMv-7HL.jp…",
    "nutrition_id": "N00002",
    "diet_id": "DA0002",
    "brand_id": "B0002",
    "category_id": "C-0005",
    "environmental_id": "E00002"
  }
]
```

## INCORRECT Inputs

> Duplicate product_id

[
  {
    "product_id": "P00001",
    "product_name": "Blueberry Passion Energy Infusion",
    "product_barcode": "3147483647",
    "product_origin": "Canada",
    "product_serving_size": 355,
    "product_image": "https://mateina.ca/cdn/shop/files/Mateina-Organic-…",
    "nutrition_id": "N00001",
    "diet_id": "DA0001",
    "brand_id": "B00001",
    "category_id": "C-0004",
    "environmental_id": "E00001"
  }
]
```

### PUT /products

## CORRECT Inputs

> Updating product P00001

```json
[
  {
    "product_id": "P00001",
    "product_name": "Blueberry Passion Energy Infusion",
    "product_barcode": "3147483647",
    "product_origin": "Canada",
    "product_serving_size": 255,
    "product_image": "",
    "nutrition_id": "N00001",
    "diet_id": "DA0001",
    "brand_id": "B00001",
    "category_id": "C-0004",
    "environmental_id": "E00001"
  }
]
```

## INCORRECT Inputs

> Updating product_origin

```json
[
  {
    "product_id": "P00001",
    "product_name": "Blueberry Passion Energy Infusion",
    "product_barcode": "3147483647",
    "product_origin": "90Canada",
    "product_serving_size": 255,
    "product_image": "",
    "nutrition_id": "N00001",
    "diet_id": "DA0001",
    "brand_id": "B00001",
    "category_id": "C-0004",
    "environmental_id": "E00001"
  }
]
```


### DELETE /products

## CORRECT Inputs

```json
["P00010","P00011"]
```

## INCORRECT Inputs

```json
["P0100","P00012"]
```



##  Examples of Inputs for /categories

### GET /categories

> Retrieves a list of categories. Results vary depending on filters and sorting specified. 

### Pagination

> Pagination uses page and page_size query parameters
> If page and page_size is not specified, the default values are 1 and 10, respectively

### Filtering

> Fields that can be filtered: category_name, category_type, parent_category 

> Query format: ?category_name=Chocolate&category_type=Snack&parent_category=Confectionery

> Data types of each filtering parameter:
> category_name = string
> category_type = string
> parent_category = string

### Sorting 

> Sorts the list by ascending or descending and can be ordered using specific fields.
> Approved ordering filters: category_name, category_type, parent_category
> Sorting parameters: sort and ordered_by
> Sorted ascending by default
> Ordered by category_id by default   
> Query format: ?sort=asc&order_by=category_name


## CORRECT Inputs

page: 2 
page_size: 10
category_name: Chocolate
category_type: Snack
parent_category: Confectionery
sort: ascending
order_by: category_name

## INCORRECT Inputs

page: 0 
page_size: 0
category_name: Chocolate99
category_type: Water
parent_category: Milk
order_by: category_level


### GET /categories/{category_id}

> Retrieves a category based on the category id

### Pagination

> Pagination uses page and page_size query parameters
> If page and page_size is not specified, the default values are 1 and 10 respectively

## CORRECT Inputs

page: 1 
page_size: 1
category_id: C-0005

## INCORRECT Inputs

page: 0 
page_size: 0
category_id: C-000


### /categories/{category_id}/brands

> Retrieves brand information based on the category id

### Pagination

> Pagination uses page and page_size query parameters
> If page and page_size is not specified, the default values are 1 and 10 respectively


### Sorting 

> Sorts the list either by ascending or descending.
> Approved ordering filters: brand_name, brand_country
> Sorting parameters: sort and ordered_by
> Sorted ascending by default
> Ordered by product_id by default   
> Query format: ?sort=asc&order_by=brand_name


## CORRECT Inputs

page: 2 
page_size: 10
category_name: Chocolate
category_type: Snack
parent_category: Confectionery
sort: ascending
order_by: category_name

## INCORRECT Inputs

page: 0 
page_size: 0
category_name: Chocolate99
category_type: Water
parent_category: Milk
order_by: category_level



### POST /categories

## CORRECT Inputs

> Category_id is required

```json
[
  {
    "category_id": "C-0010",
    "category_name": "Yogurt Drink",
    "category_description": "Drink mixed with yogurt",
    "Parent_category_id": "C-0002",
    "category_type": "Beverage",
    "category_level": "child",
    "category_tags": "dairy, drink, sweet"
  }
]
```

```json
[
  {
    "category_id": "C-0011",
    "category_name": "Sparkling Water",
    "category_description": "Sparkled water",
    "parent_category_id": null,
    "category_type": "Beverage",
    "category_level": "parent",
    "category_tags": "water, drink, sparkled"
  }
]
```

## INCORRECT Inputs

> Duplicate category_id, category_name with number

```json
[
  {
    "category_id": "C-0010",
    "category_name": "Y0ogurt Drink",
    "category_description": "Drink mixed with yogurt",
    "Parent_category_id": "C-0002",
    "category_type": "Beverage",
    "category_level": "child",
    "category_tags": "dairy, drink, sweet"
  },
  {
    "category_id": "C-0011",
    "category_name": "Sparkling Water9",
    "category_description": "Sparkled water",
    "parent_category_id": null,
    "category_type": "Beverage",
    "category_level": "parent",
    "category_tags": "water, drink, sparkled"
  }
]
```

### PUT /categories

## CORRECT Inputs

```json
[
  {
    "category_id": "C-0010",
    "category_name": "Yogurt Drink with Flavor",
    "category_description": "Flavored yogurt drink",
    "parent_category_id":  "C-0002",
    "category_type": "Beverage",
    "category_level": "parent",
    "category_tags": ""
  }
]
```

```json
[
  {
   "category_id": "C-0011",
    "category_name": "Sparkling Water",
    "category_description": "Sparkled water",
    "category_type": "Beverage",
    "category_level": "parent",
    "category_tags": "water, drink, sparkled"
  }
]
```


## INCORRECT Inputs

> Empty parent_category_id, Number in category_name

```json
[
  {
    "category_id": "C-0013",
    "category_name": "00Yogurt Drink with Flavor",
    "category_description": "Flavored yogurt drink",
    "parent_category_id": "",
    "category_type": "Beverage",
    "category_level": "parent",
    "category_tags": ""
  }
]
```

### DELETE /categories

## CORRECT Inputs

```json
["C-0010","C-0011"]
```

## INCORRECT Inputs

```json
["C-00100","P00012"]
```


##  Examples of Inputs for /allergens


### /allergens


> Retrieves a list of allergens. Results vary depending on filters and sorting specified. 

### Pagination

> Pagination uses page and page_size query parameters
> If page and page_size is not specified, the default values are 1 and 10, respectively

### Filtering

> Fields that can be filtered: allergen_name, allergen_reaction_type, food_group, food_origin, food_type

> Query format: ?allergen_name=peanut&food_group=Pulse&food_type=Eggs&food_origin=plant origin&allergen_reaction_type=hives, anaphylaxis

> Data types of each filtering parameter:
> allergen_name = string
> food_group = string
> food_type = string
> food_origin = string
> allergen_reaction_type = string

### Sorting 

> Sorts the list either by ascending or descending and can be ordered by using specific fields.
> Approved ordering filters: allergen_name, allergen_reaction_type, food_group, food_origin, food_type
> Sorting parameters: sort and ordered_by
> Sorted ascending by default
> Ordered by allergen_id by default   
> Query format: ?sort=asc&order_by=allergen_name  

## CORRECT Inputs

page: 2 
page_size: 10
allergen_name: Soy Allergy
food_group: Pulse
food_type: Cereal grain and pulse
food_origin: Plant origin
food_item: Soybean
sort: ascending
order_by: allergen_name


## INCORRECT Inputs

page: 0
page_size: 10
allergen_name: 0Soy Allergy
food_group: Pulsess
food_type: 0Cereal grain and pulse
food_origin: Plant origin
food_item: Soybean
sort: ascending
order_by: allergen_name



### /allergens/{allergen_id}

> Retrieves the allergen based on the allergen id. Results vary depending on filters and sorting specified. 

### Pagination

> Pagination uses page and page_size query parameters
> If page and page_size is not specified, the default values are 1 and 10, respectively

## CORRECT Inputs

page: 1 
page_size: 1
product_id: A01

## INCORRECT Inputs

page: 1 
page_size: 1
product_id: A-01


### /allergens/{allergen_id}/ingredients

> Retrieves the ingredient information based on allergen id. Results vary depending on filters and sorting specified. 

### Pagination

> Pagination uses page and page_size query parameters
> If page and page_size is not specified, the default values are 1 and 10, respectively

### Sorting 

> Sorts the list either by ascending or descending and can be ordered by using specific fields.
> Approved ordering filters: ingredient_name, processing_type, isGMO
> Sorting parameters: sort and ordered_by
> Sorted ascending by default
> Ordered by allergen_id by default   
> Query format: ?sort=asc&order_by=ingredient_name  
 

## CORRECT Inputs

page: 2 
page_size: 10
allergen_name: Soy Allergy
food_group: Pulse
food_type: Cereal grain and pulse
food_origin: Plant origin
food_item: Soybean
sort: ascending
order_by: allergen_name


## INCORRECT Inputs

page: 0
page_size: 10
allergen_name: 0Soy Allergy
food_group: Pulsess
food_type: 0Cereal grain and pulse
food_origin: Plant origin
food_item: Soybean
sort: ascending
order_by: allergen_name




### POST /allergens

## CORRECT Inputs

> Allergen_id, allergen_name, food_group, food_type, food_item is required
> Food_group needs to be one of the following categories: "Fruits", "Vegetables", "Pulses", "Grains", "Proteins", "Dairy", "Fats and Oils", "Sweets and Snacks", "Beverages"

```json
[
 {
    "allergen_id": "A10",
    "allergen_name": "Treenut Allergy",
    "allergen_reaction_type": "Hives, anaphylaxis",
    "food_group": "Pulses",
    "food_type": "Cereal grain and pulse",
    "food_origin": "Plant origin",
    "food_item": "Treenut"
  }
]
```

[
 {
    "allergen_id": "A11",
    "allergen_name": "Seafood Allergy",
    "allergen_reaction_type": "Hives, anaphylaxis",
    "food_group": "Proteins",
    "food_type": "Seafood",
    "food_origin": "Animal origin",
    "food_item": "Shrimp"
  },
  {
    "allergen_id": "A12",
    "allergen_name": "Treenut Allergy",
    "allergen_reaction_type": "Hives, anaphylaxis",
    "food_group": "Pulses",
    "food_type": "Cereal grain and pulse",
    "food_origin": "Plant origin",
    "food_item": "Treenut"
  }
]


## INCORRECT Inputs

```json
[
 {
    "allergen_id": "A014",
    "allergen_name": "Soy Allergy",
    "allergen_reaction_type": "Hives, anaphylaxis",
    "food_group": "",
    "food_type": "",
    "food_origin": "Plant origin",
    "food_item": "Soybean"
  }
]
```


### PUT /allergens

## CORRECT Inputs

> Food_group needs to be one of the following categories: "Fruits", "Vegetables", "Pulses", "Grains", "Proteins", "Dairy", "Fats and Oils", "Sweets and Snacks", "Beverages"

```json
[
 {
    "allergen_id": "A10",
    "allergen_name": "Treenut Allergy",
    "allergen_reaction_type": "Hives, anaphylaxis",
    "food_group": "Pulse",
    "food_type": "Cereal",
    "food_origin": "origin",
    "food_item": "Treenut"
  }
]
```

```json
[
 {
    "allergen_id": "A11",
    "allergen_name": "Allergy",
    "allergen_reaction_type": "Hives, anaphylaxis",
    "food_group": "",
    "food_type": "",
    "food_origin": "Animal origin",
    "food_item": "Shrimp"
  },
  {
    "allergen_id": "A12",
    "allergen_name": "Treenut Allergy",
    "allergen_reaction_type": "Hives, anaphylaxis",
    "food_group": "Pulses",
    "food_type": "Cereal grain and pulse",
    "food_origin": "Plant origin",
    "food_item": "Treenut"
  }
]
```



## INCORRECT Inputs

```json
[
 {
    "allergen_id": "A014",
    "allergen_name": "900Soy Allergy",
    "allergen_reaction_type": "Hives, anaphylaxis",
    "food_group": "",
    "food_type": "",
    "food_origin": "Plant origin",
    "food_item": "Soybean"
  }
]
```

### DELETE /categories

## CORRECT Inputs

```json
["A10","A11"]
```

## INCORRECT Inputs

```json
["A-10","A-1100"]
```









