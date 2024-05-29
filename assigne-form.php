<?
   session_cache_limiter('private, must-revalidate');
   session_start();
   include_once('lib.php');
   if (empty($_SESSION['usu_valido'])) {
    $usuario = $_SESSION['usu_valido'];
     include('acces.php');
	 return;
   }

   extract($_SESSION); extract($_POST); extract($_GET);
   $ui_lang = isset($_GET['lang']) ? $_GET['lang'] : "es";
// echo "<pre>"; var_dump($_SESSION["usu_valido"]);exit();
?>
<!DOCTYPE html>
<html>
<head>
<title><? include('titulo.php'); ?></title>
<meta charset="UTF-8">
<link rel="icon" href="images/favicon3.0.ico" type="image/x-icon" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link rel="stylesheet" href="https://vioniko.com/css/style.default.css" />
<link rel="stylesheet" href="https://vioniko.com/css/responsive-tables.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">

<style>
  .form-control {
    display: inline-block;
    width: auto;
    vertical-align: middle;
    padding: 0.375rem 0.75rem;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
  }
  .btn-primary {
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
  }
  .btn {
    display: inline-block;
    font-weight: 400;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    border: 1px solid transparent;
    padding: 0.6rem 2rem;
    line-height: 1.5;
    border-radius: 0.25rem;
    margin-right: 10px;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out,
      border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
  }

  .form-group {
    margin-bottom: 5px;
    overflow: hidden;
  }
  .form-control {
    min-width: 200px;
  }
  .mt-5 {
    margin-top: 10px;
  }
  .assingForm {
    margin: 50px 0 0 50px;
    width: 600px;
    padding: 20px;
    border: 1px solid #eee;
    border-radius: 10px;
  }
  .card {
    border-radius: 5px;
    width: 213px !important;
    border: 1px solid #cbc9c9;
  }
  h5.card-title {
    background: #eee;
    margin: 0;
    padding: 10px 10px;
    border-radius: 5px 5px 0 0;
    border-bottom: 1px solid #cbc9c9;
    margin-bottom: 5px;
  }
  .inner-body {
    padding: 5px;
  }
  .inner-body {
    display: flex;
    padding: 5px;
    flex-direction: column;
    row-gap: 10px;
  }
  .d-flex {
    display: flex;
    column-gap: 5px;
  }
  form.reportEntryFrom {
    display: flex;
    flex-direction: column;
    row-gap: 5px;
  }
  .userinfo > h5 {
    margin-top: 2px;
    margin-bottom: 0;
  }
  th {
    cursor: pointer;
  }
  .search-container {
    display: flex;
    justify-content: flex-end;
    margin-right: 10px;
    margin-bottom: 20px;
  }
  .search-input {
    padding: 10px;
    width: 300px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
  }
</style>

</head>

<body>

<div id="mainwrapper" class="mainwrapper">
    <div class="header">
        <div class="logo">
            <a href="principal.php"><? include('logo.php'); ?></a>
        </div>
        <div class="headerinner">
            <? include('header.php'); ?><!--headmenu-->
        </div>
    </div>

    <div class="leftpanel">

    <? include('menu.php'); ?>
    <!-- leftpanel -->
     </div><!-- leftpanel -->

    <div class="rightpanel">
        
        <?php
          include("conexion_mysqli.php");
            $sqlUserList = "SELECT u.clave, usuario, p.nombre, u.email, p.password_red, u.password_red FROM prospecto p RIGHT JOIN usuario u ON p.email = u.email WHERE usuario = ". $usuario ." AND p.password_red!='' AND p.password_red = u.password_red ORDER BY clave DESC";
            // var_dump($sqlUserList);
            $userOfSupervisor = $conn->query($sqlUserList);
            $userrows = $userOfSupervisor->fetch_all(MYSQLI_ASSOC);
            // echo "<pre>";var_dump($userrows);
            // $userOfSupervisor = $conn->query($sqlUserList);
            $className = $userOfSupervisor->num_rows <= 0 ? "d-flex":"";
            echo '<div class="assingForm '. $className .'" style="margin: 50px 0 0 50px">';
            if ($userOfSupervisor->num_rows > 0) {
          ?>
          <p class="lead">Create New form and Assigne for user.</p>
          <form class="aform">
            <div class="form-group mb-3">
              <div class="col-sm-4 text-right">
                <label for="titleId">Form Title</label>
              </div>
              <div class="col-sm-8">
                <input
                  type="text"
                  class="form-control"
                  id="titleId"
                  placeholder="Form Title"
                  name="title"
                />
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-4 text-right">
                <label for="userSelect">Select user</label>
              </div>
              <div class="col-sm-8">
                <select name="user" class="form-control">
                  <?php
                      foreach ($userrows as $row) {
                          $userId = $row['clave'];
                          $nombre = $row['nombre'];
                          echo "<option value='$userId '>$nombre</option>";
                      }
                  ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-4"></div>
              <div class="col-sm-8 mt-5">
                <button type="submit" class="btn btn-primary submitButton">
                  Assign Form
                </button>
              </div>
            </div>
          </form>
          <?php
            } else {
          $sql = "SELECT * FROM form WHERE user_id=". $usuario ." AND input_data IS null";
          // var_dump($sql);
          $result = $conn->query($sql);

            while($row = $result->fetch_assoc()) {
              $fromId = $row['id'];
              ?>
              <div class="card" id="fromId-<?=$fromId?>" style="width: 18rem">
              <div class="card-body">
                <h5 class="card-title"><?=$row['formTitle']?></h5>
                <div class="inner-body">
                <form
                  class="reportEntryFrom"
                  enctype="multipart/form-data"
                >
                  <input
                    type="text"
                    id="reportValue"
                    class="form-control"
                    name="reportValue"
                    placeholder="Sales amount"
                    required
                  />
                  <input class="reportFile" type="file" name="reportFile" required />
                  <input type='hidden' name='formId' value='<?=$fromId?>'>
                  <input class="btn btn-primary" type="submit" value="Submit" />
                </form>
                </div>
              </div>
            </div>
            <?php
              }
            } 
          ?>
        </div>

        <div class="reportResultOfUsers">
          <div id="root"></div>
        </div>
      </div><!--rightpanel-->

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://unpkg.com/react@17/umd/react.production.min.js"></script>
<script src="https://unpkg.com/react-dom@17/umd/react-dom.production.min.js"></script>
<script src="https://unpkg.com/babel-standalone@6/babel.min.js"></script>
<script type="text/babel">
  const { useState, useEffect, useMemo } = React;

  const TableComponent = () => {
    const [data, setData] = useState([]);
    const [sortConfig, setSortConfig] = useState({
      key: 'id',
      direction: "descending",
    });
    const [searchQuery, setSearchQuery] = useState("");
    const [isLoading, setLoading] = useState(true);

    // Fetch data from the API
    useEffect(() => {
      const fetchData = async () => {
        try {
          const response = await fetch(
            "https://vioniko.com/ajaxload.php?usersuperid="+<?=$usuario?>
          );
          const result = await response.json();
          setLoading(false);
          setData(result);
        } catch (error) {
          console.error("Error fetching data:", error);
        }
      };
      fetchData();
    }, []);

      // Filter data based on the search query
      const filteredData = useMemo(() => {
        return data.filter((row) => {
          const lowerCaseQuery = searchQuery.toLowerCase();
          return Object.values(row).some(
            (val) =>
              val && val.toString().toLowerCase().includes(lowerCaseQuery)
          );
        });
      }, [data, searchQuery]);

    // Sort data based on the sort configuration
    const sortedData = useMemo(() => {
      let sortableData = [...filteredData];
      if (sortConfig.key !== null) {
        sortableData.sort((a, b) => {
          let aKey =
            a[sortConfig.key] !== null
              ? a[sortConfig.key].toString().toLowerCase()
              : "";
          let bKey =
            b[sortConfig.key] !== null
              ? b[sortConfig.key].toString().toLowerCase()
              : "";

          if (aKey < bKey) {
            return sortConfig.direction === "ascending" ? -1 : 1;
          }
          if (aKey > bKey) {
            return sortConfig.direction === "ascending" ? 1 : -1;
          }
          return 0;
        });
      }
      return sortableData;
    }, [filteredData, sortConfig]);

    const requestSort = (key) => {
      let direction = "ascending";
      if (sortConfig.key === key && sortConfig.direction === "ascending") {
        direction = "descending";
      }
      setSortConfig({ key, direction });
    };

    const handleDelete = async (formId, formTitle) => {
      const confirmed = window.confirm(
        "Are you sure you want to delete " + formTitle + "?"
      );
      if (!confirmed) return null;

      try {
        fetch("https://vioniko.com/ajaxload.php", {
          method: "DELETE",
          headers: {
            "Content-Type": "application/json",
          },
          body: new URLSearchParams({
            formId: formId,
          }),
        })
          .then((response) => response.json())
          .then((json) => {
            console.log(json.formId);
            setData((prevData) =>
              prevData.filter((row) => row.id !== formId)
            );
          })
          .catch((error) => {
            // Network error
            console.error("Network error:", error);
            // alert("Network error occurred while trying to delete user.");
          });
      } catch (error) {
        console.error("Error deleting data:", error);
      }
    };

    return (
      <div>
        <div className="search-container">
            <input 
                type="text" 
                className="search-input"
                placeholder="Search..." 
                value={searchQuery}
                onChange={e => setSearchQuery(e.target.value)} 
            />
        </div>


        <table className="table table-striped table-bordered table-hover">
          <thead>
            <tr>
              <th onClick={() => requestSort("id")}>ID</th>
              <th onClick={() => requestSort("nombre")}>NOMBRE</th>
              <th onClick={() => requestSort("email")}>EMAIL</th>
              <th onClick={() => requestSort("formTitle")}>FORM TITLE</th>
              <th>FORM DATA</th>
              <th>REPORT FILE</th>
              <th onClick={() => requestSort('submission_date')}>SUBMISSION</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          {isLoading &&"Loading..."}
            {sortedData.map((row, index) => (
              <tr key={index}>
                <td>{row.id}</td>
                <td>{row.nombre}</td>
                <td>{row.email}</td>
                <td>{row.formTitle}</td>
                <td>{row.input_data}</td>
                <td><a href={`uploads/${row.reportFile}`} target="_blank" rel="noopener noreferrer">{row.reportFile}</a></td>
                <td>{row.submission_date}</td>
                <td>
                  <button
                    className="btn btn-danger btn-sm"
                    onClick={() => handleDelete(row.id, row.formTitle)}
                  >
                    &#10006;
                  </button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    );
  };

  ReactDOM.render(<TableComponent />, document.getElementById("root"));
</script>
<script type="text/javascript">
      // Delete Form row
      $(document).ready(function() {
        $("button.btn-danger").on("click", function () {
          console.log('first');
          const formId = $(this).data("formid");
          const formTitle = $(this).data("formtitle");
          // Show a confirmation dialog
          const confirmed = confirm(
            "Are you sure you want to delete " + formTitle + "?"
          );

          if (confirmed) {
            fetch("https://vioniko.com/ajaxload.php", {
              method: "DELETE",
              headers: {
                "Content-Type": "application/json",
              },
              body: new URLSearchParams({
                formId: formId,
              }),
            })
              .then((response) => response.json())
              .then((json) => {
                console.log(json.formId);
                $(this).closest("tr").remove();
              })
              .catch((error) => {
                // Network error
                console.error("Network error:", error);
                // alert("Network error occurred while trying to delete user.");
              });
          }
        });

        // Submittion Form
        $(".reportEntryFrom").submit(function (event) {
          event.preventDefault();
          // Create a FormData object
          var formData = new FormData(this);

          var $submitButton = $(this).find('input[type="submit"]');
          $submitButton.prop("disabled", true).val("Submitting");
          // Log the value to the console
          // console.log("formData:", formData);
          // Send the data using AJAX
          $.ajax({
            url: "https://vioniko.com/ajaxload.php",
            type: "POST",
            data: formData,
            contentType: false, // Tell jQuery not to process the data
            processData: false, // Tell jQuery not to set contentType
            success: function (response) {
              const obj = jQuery.parseJSON(response);
              
              $submitButton.prop("disabled", true).html("Done &#10003;");
              setTimeout(function () {
                $("#fromId-" + obj.formId).hide();
              }, 2000);
              console.log("Success:", response);
              // Handle the response here (e.g., display a success message)
            },
            error: function (jqXHR, textStatus, errorThrown) {
              console.error("Error:", textStatus, errorThrown);
              // Handle the error here (e.g., display an error message)
            },
          });
        });

        $(".aform").submit(function (event) {
          event.preventDefault();
          $('.submitButton').prop('disabled', true).text("Submitting");

          const formDataManually = {
            title: $('input[name="title"]').val(),
            user: $('select[name="user"]').val(),
            formAssign: true,
          };
          this.reset(); // Reset the form
          console.log(formDataManually);
          // Make an AJAX POST request
          $.ajax({
            url: "https://vioniko.com/ajaxload.php",
            type: "POST",
            data: formDataManually,
            success: function (response) {
              $('.submitButton').prop('disabled', false).html("Done &#10003;");
              $('input[name="title"]').val()
              this.reset(); // Reset the form
            },
            error: function (jqXHR, textStatus, errorThrown) {
              console.error("Error:", textStatus, errorThrown);
              // Handle the error here (e.g., display an error message)
            },
          });
        });
      });
    </script>
</body>
</html>
