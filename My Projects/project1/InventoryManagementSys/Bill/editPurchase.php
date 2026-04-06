
<?php
include("../dbconnect.php");

$invoice_id = $_GET['invoice_id'];

// Fetch invoice header info
$sql_invoice = "SELECT * FROM purchase_invoices 
                JOIN sellers ON purchase_invoices.seller_id = sellers.seller_id
                WHERE invoice_id = $invoice_id";
$invoice = mysqli_fetch_assoc(mysqli_query($conn, $sql_invoice));

// Fetch invoice products (line items)
$sql_items = "SELECT * FROM purchases
              JOIN products ON purchases.product_id = products.product_id
              WHERE invoice_id = $invoice_id";
$result_items = mysqli_query($conn, $sql_items);
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Invoice Entry</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
   
  <link rel="stylesheet" href="../Sales/sales.css">
</head>
<body>
  <h2>Update Purchase Entry</h2>
  <div class="container-fluid">
    <form id="invoiceForm" action="purchaseEdited.php" method="post">
      <div class="row">
        <!-- Invoice header  -->
        <div class="col-2  ">
          <input type="hidden" id="invoice_id" name="invoice_id" value="<?= $invoice_id ?>">
          <input type="hidden" name="seller_id" value="<?= $invoice['seller_id'] ?>">
          <label for="sellerSelect">Seller:</label>
          <select type="text" class="customer" id="sellerSelect" name="seller_Name" value="">
            <option value="">Select Seller</option>
</select>
        </div>
        <div class="col-2 d-flex align-items-center w-auto">
          <label for="invoiceid" class="mb-4">Invoice No:</label>
          <input type="text" id="invoiceid" name="invoice_no" class="invoice" value="<?=$invoice['invoice_no'] ?>" readonly/>
          
        </div>
        <div class="col-2">
          <label for="date">Date:</label>
          <input type="date" id="date" name="invoice_date"value="<?=$invoice['invoice_date']?>" required />
        </div>
        <div class="col-2 d-flex align-items-center w-auto">
          <label for="paymentTerm" class="mb-4">Payment Term:</label>
          <select class="payment" id="paymentTerm" name="payment_term"  required>
            <option value="<?=$invoice['payment_term']?>"><?=$invoice['payment_term']?></option>
            <option value="cash">Cash</option>
            <option value="credit">Credit</option>
          </select>
        </div>
        <div class="col-2 d-flex align-items-center w-auto">
          <label class="mb-4 gt text-success"><strong>Grand Total: </strong></label>
          <input type="text" id="grandTotal" name="grand_total" class="readonly grandTotl"value="<?=$invoice['grand_total']?>" readonly value="0.00" />
        </div>
        <hr id="hrline" />
      </div>
      <!-- Products table -->
      <table class="table table-bordered mx-auto border-secondary" id="productsTable">
        <thead>
            
          <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Unit</th>
            <th>Unit Price</th>
            <th>Discount %</th>
            <th>Tax %</th>
            <th>Total</th>
            <th>Remove</th>
          </tr>
        </thead>

        <tbody>
            <?php while($item=mysqli_fetch_assoc($result_items)):?>
          
          <tr>
            <td>
              <input type="text" name="product_name" 
              class="productSearch temp productSelect"id="selectProduct" list="productList"
               data-id="<?=$item['product_id']?>" 
               value="<?=$item['product_name']?>" 
               placeholder="Select Product" required>
              <datalist class="" id="productList"></datalist>
              <div id="selectedProductInfo"></div>
            </td>
            <td><input type="number" name="quantity[]" class="quantityInput" value="<?=$item['quantity']?>" required /></td>
            <td><input type="text" class="unitDisplay readonly"value="<?=$item['measure_unit']?>" readonly /></td>
            <td><input type="number" class="unitPriceInput" value="<?=$item['unit_price']?>" required /></td>
            <td><input type="number" class="discountInput" value="<?=$item['discount']?>" /></td>
            <td><input type="number" class="taxInput" value="<?=$item['tax']?>" /></td>
            <td><input type="text" class="totalDisplay readonly"value="<?=$item['total']?>" readonly  /></td>
            <td><button type="button" class="removeBtn btn btn-danger my-1">X</button></td>
          </tr>
          <!-- <input type="text" name="product_name" class="productSearch productSelect" data-id="<?= $item['product_id'] ?>" value="<?=$item['product_name']?>" placeholder="select product" required> -->
          <?php endwhile; ?>
        </tbody>
       
      </table>
      <div>
        <button id="addProductBtn" type="button" class="btn btn-primary">Add Product</button>
      </div>
      <hr />
      <br /><br><br>
    </form>
  </div>
  <div class="justify-content-center saveb">
    <button type="submit" form="invoiceForm" class="position-fixed bottom-0 end-0  mb-0 save-btn">Update Purchase</button>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
  <script>
//     const today = new Date().toISOString().split('T')[0];
//     document.getElementById("date").value = today;
// const customerMap = new Map(); // Optional: to map names to IDs if needed
document.addEventListener("click", function(e) {
    if (e.target.classList.contains("removeBtn")) {
        e.target.closest("tr").remove(); // remove row from table
         calculateGrandTotal(); // recalc total
    }
});
async function loadSeller() {
      try {
        let response = await fetch("../Purchase/loadSeller.php"); // call backend
        let data = await response.json();

        let dropdown = document.getElementById("sellerSelect");
        dropdown.innerHTML = `<option value="">` + <?= json_encode($invoice['seller_Name']) ?> + `</option>`;


        data.forEach(seller => {
          let option = document.createElement("option");
          option.value = seller.seller_id;
          option.textContent = seller.seller_Name;
          dropdown.appendChild(option);
          console.log(data);
        });
      } catch (error) {
        console.error("Error loading sellers:", error);
      }
    }
    window.addEventListener("load", loadSeller);

   async function loadProduct() {
  try {
    let response = await fetch('../Product/loadProduct.php');
    let data = await response.json();

    let dropdown = document.getElementById('productList');
    dropdown.innerHTML = ''; // reset

    data.forEach(product => {
      let option = document.createElement('option');
      option.value = product.product_name;   // what user sees
      option.dataset.id = product.product_id; // real ID
      option.dataset.unit = product.measure_unit;
      option.dataset.price = product.unit_price;
      dropdown.appendChild(option);
      
    });

    // Attach listener to existing product select
    document.querySelectorAll('.productSelect').forEach(input => {
      attachProductListener(input, dropdown);
    });

  } catch (error) {
    console.error('Error loading products:', error);
  }
}

window.addEventListener("load", loadProduct);

// 🔥 function to attach listener (reusable for new rows)
function attachProductListener(input, dropdown) {
  input.addEventListener('input', () => {
    let val = input.value;
    let option = Array.from(dropdown.options).find(opt => opt.value === val);

    if (option) {
      input.dataset.id = option.dataset.id;

      let row = input.closest('tr');
      row.querySelector('.unitDisplay').value = option.dataset.unit;
      row.querySelector('.unitPriceInput').value = option.dataset.price;

      calculateRowTotal(row);
    } else {
      input.dataset.id = '';
    }
  });
}

    const productsTable = document.getElementById('productsTable').getElementsByTagName('tbody')[0];
    const addProductBtn = document.getElementById('addProductBtn');
    const grandTotalInput = document.getElementById('grandTotal');

    // Add event listeners for existing row
    function addRowListeners(row) {
      const quantityInput = row.querySelector('.quantityInput');
      const unitPriceInput = row.querySelector('.unitPriceInput');
      const discountInput = row.querySelector('.discountInput');
      const taxInput = row.querySelector('.taxInput');
      const removeBtn = row.querySelector('.removeBtn');

      [quantityInput, unitPriceInput, discountInput, taxInput].forEach(input => {
        input.addEventListener('input', () => {
          calculateRowTotal(row);
        });
      });

      removeBtn.addEventListener('click', () => {
        if (productsTable.rows.length > 1) {
          row.remove();
          calculateGrandTotal();
        } else {
          alert('At least one product is required.');
        }
      });
    }

    function calculateRowTotal(row) {
      const qty = parseFloat(row.querySelector('.quantityInput').value) || 0;
      const price = parseFloat(row.querySelector('.unitPriceInput').value) || 0;
      const discount = parseFloat(row.querySelector('.discountInput').value) || 0;
      const tax = parseFloat(row.querySelector('.taxInput').value) || 0;

      let subtotal = qty * price;
      let discountAmount = subtotal * (discount / 100);
      let taxableAmount = subtotal - discountAmount;
      let taxAmount = taxableAmount * (tax / 100);
      let total = taxableAmount + taxAmount;

      row.querySelector('.totalDisplay').value = total.toFixed(2);
      calculateGrandTotal();
    }

    function calculateGrandTotal() {
      let grandTotal = 0;
      productsTable.querySelectorAll('tr').forEach(row => {
        const total = parseFloat(row.querySelector('.totalDisplay').value) || 0;
        grandTotal += total;
      });
      grandTotalInput.value = grandTotal.toFixed(2);
    }

    // Clone row on Enter press
    productsTable.addEventListener('keydown', function (event) {
      if (event.key === 'Enter') {
        if (event.target.classList.contains('taxInput')) {
          event.preventDefault();

          const newRow = productsTable.rows[0].cloneNode(true);

          newRow.querySelector('.productSelect').value = '';
          newRow.querySelector('.unitDisplay').value = '';
          newRow.querySelector('.quantityInput').value = 1;
          newRow.querySelector('.unitPriceInput').value = '';
          newRow.querySelector('.discountInput').value = 0;
          newRow.querySelector('.taxInput').value = 0;
          newRow.querySelector('.totalDisplay').value = '0.00';
          //newRow.querySelector('#selectProduct')?.removeAttribute('id'); //isko nikal sakte hai... if not work remove it
          productsTable.appendChild(newRow);
          //productsTable.querySelector('tbody').appendChild(newRow);//if not work isko nikal ke uncomment the above row
          addRowListeners(newRow);

          // 🔥 attach product listener for new row
          let dropdown = document.getElementById('productList');
          attachProductListener(newRow.querySelector('.productSelect'), dropdown);
        }
      }
    });

    addRowListeners(productsTable.rows[0]);

    document.getElementById('invoiceForm').addEventListener('submit', (e) => {
      e.preventDefault();

      const sellerId = document.getElementById('sellerSelect').value;
      const invoiceNo = e.target.invoice_no.value;
      const invoiceId = e.target.invoice_id.value;
      const invoiceDate = e.target.invoice_date.value;
      const paymentTerm = e.target.payment_term.value;
      const grandTotal = grandTotalInput.value;

      const products = [];
      productsTable.querySelectorAll('tr').forEach(row => {
        const productSelect = row.querySelector('.productSelect');
        const productId = productSelect.dataset.id;
        const quantity = row.querySelector('.quantityInput').value;
        const unit = row.querySelector('.unitDisplay').value;
        const unitPrice = row.querySelector('.unitPriceInput').value;
        const discount = row.querySelector('.discountInput').value;
        const tax = row.querySelector('.taxInput').value;
        const total = row.querySelector('.totalDisplay').value;

        if (productId) {
          products.push({
            product_id: productId,
            quantity: parseFloat(quantity),
            unit: unit,
            unit_price: parseFloat(unitPrice),
            discount: parseFloat(discount),
            tax: parseFloat(tax),
            total: parseFloat(total)
          });
        }
      });

      const data = {
        invoice_id: invoiceId,
        seller_id: sellerId,
        invoice_no: invoiceNo,
        invoice_date: invoiceDate,
        payment_term: paymentTerm,
        grand_total: parseFloat(grandTotal),
        products: products
      };
      console.log("Sending data:", data);
      fetch("purchaseEdited.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
      })
        .then(res => res.json())
        .then(response => {
          alert(response.message);
          console.log(response);
          window.location.href = "../Dashboard/sellerPayment.php?invoice_id=" + data.invoice_id;
        })
        .catch(error => {
          console.error("Error saving purchase:", error);
        });
    });
   
  </script>

</body>
</html>
