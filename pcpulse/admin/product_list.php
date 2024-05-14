<!--
    ------------------------------------------------------
    Project Name: PcPulse- an online computer and accesories selling ecommerce store
    Group: 1
    Members:
            Shree Dhar Acharya
            Prashant Sahu
            Abhijit Singhs
            Karamjot Singh
    -------------------------------------------------------
-->

<?php
include_once '../dbinit.php';
include_once 'Product.php';


$db = new Database("localhost", "root", "", "pcpulse");
$product = new Product($db);

?>

<div>
        <h1 class="mb-4">Products List
            <a href="add_product.php" class="btn btn-primary float-right"><i class="fas fa-plus-circle"></i> Add Product</a>
        </h1>
        <hr />
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">SN</th>
                        <th scope="col">Name</th>
                        <th scope="col">Description</th>
                        <th scope="col">Brand</th>
                        <th scope="col">In Stock</th>
                        <th scope="col">Price</th>
                        <th scope="col">Updated By</th>
                        <th colspan="2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $serialNumber = 1;
                    $products = $product->readProducts();

                    while ($row = $products->fetch_assoc()) {
                        echo "<tr>";
                        echo "<th scope='row'>$serialNumber</th>";
                        echo "<td><a href='product_detail.php?id=" . htmlspecialchars($row['ProductID']) . "'>" . htmlspecialchars($row['Name']) . "</a></td>";
                        echo "<td>" . htmlspecialchars($row['Description']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Brand']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['InStock']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Price']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['UpdatedBy']) . "</td>";
                        echo "<td><a href='edit_product.php?id=" . htmlspecialchars($row['ProductID']) . "' class='btn btn-sm btn-info'><i class='fas fa-pen'></i> Edit</a></td>";
                        echo "<td>
                            <form action='" . $_SERVER['PHP_SELF'] . "' method='POST' onsubmit='return confirm(\"Are you sure you want to delete this product?\");'>
                                <input type='hidden' name='ProductID' value='" . htmlspecialchars($row['ProductID']) . "'>
                                <button type='submit' class='btn btn-sm btn-danger'><i class='fas fa-trash'></i> Delete</button>
                            </form>
                        </td>";
                        echo "</tr>";
                        $serialNumber++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>