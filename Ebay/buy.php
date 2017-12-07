<!--This is html and php script for display and interation of a shopping website, using the eBay and Shopping.com api
      The program uses the demo API & and the tracking ID provided by eBay.
-->


<html>
<head>
    <title>eBuy Products</title>
    <!--set style parameter for the html elements-->
    <style>
        table {text-align:center  }
        td {height:110px;  }
        tr:hover {  background-color:#B0C800 }
    </style>
</head>

<?php
session_start();                             //start a new session for the user when the window is opened.
error_reporting(0);
?>

<body style="background-color:Teal">
  <h2 style="text-align:center;"> eBuy Shopping Basket</h2>

<?php
//php script forr adding the products into the users basket.

if (!isset($_SESSION['basket'])) { //   check for initialization of cart items array
    $_SESSION['basket'] = array ();  // if not initialized, initializes it
}

if (!isset($_SESSION['search_history'])) { //   check for initialization of cart items array
    $_SESSION['search_history'] = array ();
       // if not initialized, initializes it
}
if(!isset($_SESSION['st_val'])){
  $_SESSION['st_val'] = 0;
}

if(!isset($_SESSION['basket_cost'])) {
    $_SESSION['basket_cost'] = (float)0;  // initialize the total price for the cart items to 0
}

if($_GET['prod_buy']) {                 //     will run only when the prod_buy condition is true i.e. The user clicks on the image or the link "Add to cart"
    $listItem = $_GET['prod_buy'];

    $prod_info = file_get_contents("http://sandbox.api.ebaycommercenetwork.com/publisher/3.0/rest/GeneralSearch?apiKey=78b0db8a-0ee1-4939-a2f9-d3cd95ec0fcc&visitorUserAgent&visitorIPAddress&trackingId=7000610&productId=".$listItem);
    $prodDetails = new SimpleXMLElement($prod_info);
    //get product details from the returned results.
    //Insert the data into the basket if the product does not exist already.
    if(array_key_exists($_GET['prod_buy'],$_SESSION['basket'])==false) {
        $prod_add_resp = "<br /> Product added to the cart";
				$_SESSION['basket_cost'] += (float)$prodDetails->categories->category->items->product->minPrice;
		    $_SESSION['basket'][$_GET['prod_buy']]['id'] = (double)$prodDetails->categories->category->items->product['id'];
		    $_SESSION['basket'][$_GET['prod_buy']]['name'] = (string)$prodDetails->categories->category->items->product->name;
		    $_SESSION['basket'][$_GET['prod_buy']]['image'] = (string)$prodDetails->categories->category->items->product->images->image[0]->sourceURL;
        $_SESSION['basket'][$_GET['prod_buy']]['price'] = (float)$prodDetails->categories->category->items->product->minPrice;
		    $_SESSION['basket'][$_GET['prod_buy']]['url'] = (string)$prodDetails->categories->category->items->product->productOffersURL;
    }
    else {
        $prod_add_resp = $it_name ."<br /> Product preset in the Basket";
    }
}
?>

<?php
//store the previous results in the array.
function store_results($id, $name, $min_p, $max_p, $desc, $url, $st){

    $_SESSION['search_history'][$st]['id'] = $id;
    $_SESSION['search_history'][$st]['name'] = $name;
    $_SESSION['search_history'][$st]['min_p'] = $min_p;
    $_SESSION['search_history'][$st]['max_p'] = $max_p;
    $_SESSION['search_history'][$st]['desc'] = $desc;
    $_SESSION['search_history'][$st]['url'] = $url;
    $_SESSION['search_history'][$st]['image'] = $image;
    return;
}
 ?>

<?php
//php script to obtain the input details like search keyword and category

if($_GET['usr_inp']) {
    if(!isset($usr_key)) {                   //get the search keyword and set it
        $usr_key = urlencode($_GET["search_key"]);
    }
    if(!isset($cat_ID)) {                   //get the category of the selected option
        $cat_ID = $_GET["drop_cat"];
    }
    if($usr_key == null) {
        $usr_resp = "Enter Product name for searching the server";
    }
    else {                                          //obtain the products from the server using the API.
        $ser_resp = file_get_contents("http://sandbox.api.shopping.com/publisher/3.0/rest/GeneralSearch?apiKey=78b0db8a-0ee1-4939-a2f9-d3cd95ec0fcc&visitorUserAgent&visitorIPAddress&trackingId=7000610&categoryId=". $cat_ID . "&keyword=" . $usr_key . "&numItems=20&showProductOffers=false");
        $ser_result = new SimpleXMLElement($ser_resp);
    }
    if($ser_result->categories->category->items == null){
      $no_resp = "No products available for the Keyword provided";                            //When no products were returned.
    }
}
?>

<?php
//php script for claring the basket and removing all the elements/products in the basket.

if($_GET['basket_emp']) {
    if(!empty($_SESSION['basket'])) {
        unset($_SESSION['basket']);
        $_SESSION['basket_cost'] = 0;                                                           //set cost to 0
        $bask_emp_resp = "<p> Basket Now Empty...Start Fresh mate!!! </p>";
    }
     else {
        $bask_emp_resp = "<p>No Products in Basket to Empty</p>";
    }
}
?>

<?php
//Php script to delete a product from the basket. If the product exits in the basket, its id is taken as a key and the price is updated and..
//the product is removed from the session array .. i.e the basket.
if($_GET['bask_del_prod']) {
    $prod_del = $_GET['bask_del_prod'];                 //get product details

    if(array_key_exists($_GET['bask_del_prod'],$_SESSION['basket'])==true) {
        $_SESSION['basket_cost'] -= (float)$_SESSION['basket'][$prod_del]['price'];         //update price
        $Prod_del_resp = "<br /> Product Removed from Basket";
    }
    else {
        $Prod_del_resp= "<b>Product Not in Basket</b> </p>";
    }
    unset($_SESSION['basket'][$prod_del]);                  //remove from basket
}
?>


<!-- Html and Php script for the input box and keyword search. The box also displays all the children of the category provided.-->

<div>
    <form action="buy.php" method="GET">
      <h5 style="margin-left:30px">Search Box</h5>
        <?php
        $categoryTreeStr = file_get_contents("http://sandbox.api.ebaycommercenetwork.com/publisher/3.0/rest/CategoryTree?apiKey=78b0db8a-0ee1-4939-a2f9-d3cd95ec0fcc&visitorUserAgent&visitorIPAddress&trackingId=7000610&categoryId=72&showAllDescendants=true");
        $categoryTree = new SimpleXMLElement($categoryTreeStr);
        ?>                                                                  <!--//obtain the categories-->

        <fieldset>
            <label style="margin-left:15px">Category:&nbsp;</label>                       <!--//configure the search box-->
            <select name="drop_cat" style="width:270px;">
                <?php
                foreach($categoryTree->category as $selectCategory) {                                         //display the categories
                    echo "<option value='".$selectCategory['id']."'>".$selectCategory->name."</option>" ;
                    foreach($selectCategory->categories->category as $categoryGroup) {
                        echo "<option value='" . $categoryGroup['id'] . "'>".$categoryGroup->name."</option>";
                        echo "<optgroup label='" . $categoryGroup->name . "' value='" . $categoryGroup['id'] . "'>" . "\n";
                        foreach ($categoryGroup->categories as $cat) {
                            foreach ($cat->children() as $subCat) {
                                echo "<option value='" . $subCat['id'] . "'>" . $subCat->name . "</option>";
                            }
                        }
                        echo "</optgroup>";
                    }
                }
                ?>
            </select>                                                                                                                     <!--//input box and buttons for user-->
            <input  name="search_key" type="text" placeholder="Search here" style="height:32px;width:280px;display:inline;margin:0px" />
            <button  name="usr_inp" type="submit" value="submitted" style="height:32px;margin0px;">Search Items</button>
            <button  type="submit" name="basket_emp" value="empty" style="height:32px;margin0px;">Clear Cart</button>
        </fieldset>
    </form>
</div>


<!--The messages of the actions performed are displayed here-->

<div style="text-align:center"> <?= $bask_emp_resp ?> </div>
<div style="text-align:center"><p><?= $Prod_del_resp ?></p></div>
<div style="text-align:center"><p><?= $prod_add_resp ?></p></div>
<div style="text-align:center"><p><?= $no_resp ?></p></div>


<!--Html and Php script for the display of the user basket. The basket displays the image, name, min price, remove and product details link-->

<div style="height:180px;margin-bottom:150px;">
  <h5 style="text-align:center">User's Basket</h5>
    <div style="height:100px;margin-top:10px">
        <p>Product Count: <?= count($_SESSION['basket']) ?> &nbsp;&nbsp;&nbsp; Basket Cost: $<?= $_SESSION['basket_cost'] ?></p
        >
    </div>
    <div>
        <div style="height:200px;overflow:auto;">
            <table >
                <?php
                foreach($_SESSION['basket'] as $prod_list) {?>
                    <tr >
                        <td><img src="<?= $prod_list['image']?>"/></a></td>
                        <td><?= $prod_list['name']?></td>
                        <td style="text-align:center">$<?= $prod_list['price'] ?></td>
                        <td style="text-align:center">
                            <a href="buy.php?bask_del_prod=<?= $prod_list['id'] ?>"> Remove </a>
                        </td>
                        <td style="margin-left:40x;text-align:center">
                             <a href="<?= $prod_list['url'] ?>" target="_blank">  View Product Details</a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>


<!-- Html and Php script for the display of Search results.-->

<div >
  <h5 style="text-align:center">Search Results</h5>
  <div style="height:285px;overflow:auto;">
    <table>
      <?php
        $st_val = 0;
        foreach ($ser_result->children() as $resxml) {
          if($resxml->getName() == "categories") {
            foreach ($resxml->category->items->product as $prods) {
              $p_Name = $prods->name;                                                               //obtain the product details 1 after another
              $p_id = $prods['id'];
              $p_price_mi = $prods->minPrice;
              $p_price_ma = $prods->maxPrice;
              $p_desc = $prods->fullDescription;
              $p_url = $prods->productOffersURL;
              $p_image = $prods->images->image[0]->sourceURL;
              store_results($p_id, $p_Name, $p_price_mi, $p_price_ma, $p_desc, $p_url, $st_val);
              $st_val++;
              echo "<tr>";                                                                        //display the product details in a table
              echo "<td><a href=buy.php?prod_buy=". $p_id ."><img src='" . $p_image . "' /></a></td>";
              echo "<td>" . $p_Name . "</td>";
              echo "<td style='width:65px;'><p>$". $p_price_mi . "</p></td>";
              echo "<td style='width:65px;'><p>$". $p_price_ma . "</p></td>";
              echo "<td>
                  <a href=".$p_url ."target=_blank> Product Details</a>";
              echo "<td>" . $p_desc . "</td>";
              echo "<td><a href=buy.php?prod_buy=". $p_id .">Add to Cart</a></td>";
              echo "</tr>";
          }
      }
    }
?>
  </table>
  </div>
</div>


<!--printing search history-->
<div>
  <h5 style="text-align:center">Previous Results</h5>
  <div style="height300px;overflow:auto;">
        <table >
          <?php
          //foreach($_SESSION['search_history'] as $id => $prod_list) {?>
              <tr >
                  //  <td><?= //$prod_list['name']?></td>
                    //<td style="text-align:center">$<?= //$prod_list['min_p'] ?></td>
                    //<td style="text-align:center">$<?= //$prod_list['max_p'] ?></td>
                    //<td style="text-align:center">$<?= //$prod_list['desc'] ?></td>
                </tr>
              <?php } ?>
        </table>
    </div>
</div>-->

</body>
</html>
