# Project Dicussion Notes:

## 2 Big Questions

 ### 1. How do we know that an APT is present?
  We are trying to find difference between malicious APT behavior and non-APT behavior. If we know that it is an APT, its much easier to pin point which APT it is.

 ### 2. How do we know which APT is present?
  We look for the known indicators for a list of APTs and check what matches (has the most hits). With those results we can say that a certain APT probably exists1.
##

## Database schema ideas

```

--------------------------------------
| APT | Possible Techniques | Events |
--------------------------------------
|APT 3| 	45	    |	~50  |
--------------------------------------

```

## Probabilities

### What is the probability that we have detected an APT?

## Idea 1:

```
 num of techniques found 	num of categories hit
 --------------------------  *	---------------------
 num of possible techniques 	  num of categories

```

## Idea 2 (refined):

```
 num of observed techniques 	   num of observed techniques	        categories of possible techniques 
 --------------------------   *  --------------------------------   *	---------------------------------
 num of possible techniques      categories of possible techniques   	  num of possible categories

```


