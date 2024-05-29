# React use in existing project using CDN

**If js and all code in one file then no need server. If js is in separate file then need server.** <br>
**babel.min.js: for jsx to js**

```html
<!-- index.html -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>React User Details App</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
  </head>
  <body>
    <div id="root" data-userid="3339"></div>

    <!-- React CDN -->
    <script src="https://unpkg.com/react@17/umd/react.production.min.js"></script>
    <script src="https://unpkg.com/react-dom@17/umd/react-dom.production.min.js"></script>
    <script src="https://unpkg.com/babel-standalone@6/babel.min.js"></script>

    <!-- App component script -->
    <script type="text/babel" src="app.js"></script>
  </body>
</html>
```

**It is not possible to keep different component in different file. Export and Import is not possible here. So In One file I do so**

```js
// app.js
// User component to display user details
function User({ user, onClick }) {
  console.log(user.messages);
  return (
    <main className="flex">
      <div className="userDetails">
        <button className="btn" onClick={() => onClick(null)}>
          &#8617; Back
        </button>
        <h2>User Chat</h2>
        <p>User Name: {user.name}</p>
        <p>phone: {user.phone}</p>
        <p>Email: {user.email}</p>
      </div>
      <div className="msgList">
        <h1>Messages</h1>
        {user.messages.map((message, i) => (
          <p key={i} className={message.role}>
            {message.role == "user" ? user.name : "Assistant"}
            {": "}
            {message.content}
          </p>
        ))}
      </div>
    </main>
  );
}

function ApiKey(props) {
  const [apikey, setApiKey] = React.useState("");
  const [savedInfo, setSavedInfo] = React.useState("");

  // console.log(props.userid);
  const submit = (e) => {
    e.preventDefault();

    fetch("https://vioniko.com/api.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ apikey: apikey, userid: props.userid }),
    })
      .then((response) => response.json())
      .then((data) => {
        setSavedInfo(data);
        console.log(data);
      })
      .catch((error) =>
        console.error("There was a problem with the fetch operation:", error)
      );
  };

  return (
    <form
      name="forma_busqueda"
      id="forma_busqueda"
      method="post"
      action="lista_prospectos.php"
      className="searchbar"
      onSubmit={submit}
    >
      <input
        type="text"
        placeholder="API Key"
        value={apikey}
        onChange={(e) => setApiKey(e.target.value)}
      />
      <button className="btn" type="submit">
        Save
      </button>

      {savedInfo && <p>{savedInfo.message}</p>}
    </form>
  );
}

// App component
function App() {
  const [selectedUser, setSelectedUser] = React.useState(null);
  const [users, setUsers] = React.useState([]);
  const [selectedFileName, setFileName] = React.useState("all");
  const [error, setError] = React.useState("");
  const [loading, setLoading] = React.useState(true);

  const article = document.querySelector("#root");

  React.useEffect(() => {
    // fetch("https://vioniko.com/api.php?userid=" + article.dataset.userid) // From live server
    fetch("/userdata.json?userid=" + article.dataset.userid)
      .then((response) => response.json())
      .then((data) => {
        // console.log(data);
        if (data.error) {
          setLoading(false);
          setError(data.error);
        } else {
          setUsers(data);
        }
        setLoading(false);
      })
      .catch((error) => {
        console.error("Error fetching users:", error);
        setLoading(false);
      });
  }, []);

  const filesName = users.reduce((acc, val) => {
    const topLevelName = val.fileName; // Extracting top-level name
    if (topLevelName && !acc.includes(topLevelName)) {
      acc.push(topLevelName);
    }
    return acc;
  }, []);

  // Filter items based on the selected category
  const filteredUsers =
    selectedFileName === "all"
      ? users
      : users.filter((user) => user.fileName === selectedFileName);

  // Function to handle user click
  function handleUserClick(user) {
    setSelectedUser(user);
  }

  return (
    <div>
      <div className="pageheader flex">
        <ApiKey userid={article.dataset.userid} />

        <div className="pagetitle">
          <h1>Chat Vioniko</h1>
        </div>
      </div>
      <div className="ecommerce chatVioniko">
        <div className="flexHeader">
          <img
            src="https://www.chatvioniko.com/_next/static/media/Logo-Blue.8b6e9fdd.svg"
            alt="chatvioniko"
            width="100px"
          />

          <select
            className="form-select mb-3"
            onChange={(e) => setFileName(e.target.value)}
          >
            <option value="all">All Files Name</option>
            {filesName.map((fileName) => (
              <option key={fileName} value={fileName}>
                {fileName}
              </option>
            ))}
          </select>

          <h3>Clientes pdf</h3>
        </div>

        {error ? (
          <h2>Set Key</h2>
        ) : loading ? (
          <p>Loading ....</p>
        ) : selectedUser ? (
          <User user={selectedUser} onClick={handleUserClick} />
        ) : (
          <table id="table1">
            <thead>
              <tr>
                <th>NOMBRE</th>
                <th>EMAIL</th>
                <th>TELÉFONO</th>
                <th>DÍA</th>
                <th>HORA</th>
                <th>NOMBRE DEL ARCHIVO</th>
              </tr>
            </thead>
            <tbody>
              {filteredUsers.map((user) => {
                if (user.hasOwnProperty("fileName"))
                  return (
                    <tr
                      className="nameColum"
                      key={user.chatId}
                      onClick={() => handleUserClick(user)}
                    >
                      <td>{user.name}</td>
                      <td>{user.email}</td>
                      <td>{user.phone}</td>
                      <td>{user.day}</td>
                      <td>{user.time}</td>
                      <td>{user.fileName}</td>
                    </tr>
                  );
              })}
            </tbody>
          </table>
        )}
      </div>
    </div>
  );
}

// Render the App component
ReactDOM.render(<App />, document.getElementById("root"));
```

```css
.btn {
  border: 1px solid;
  border-radius: 3px;
  margin-right: 2px;
}
.ecommerce {
  margin: 10px;
  padding: 10px;
}
.flex {
  display: flex;
  gap: 50px;
}
.flexHeader {
  display: flex;
  justify-content: space-between;
  margin-bottom: 5px;
}

table,
td,
th {
  border: 2px solid #fff;
}

#table1 {
  border-collapse: collapse;
  width: 100%;
}
th {
  background: #2980b9;
  padding: 5px 10px;
  color: #fff;
  text-align: center;
}
td {
  background: #eee;
  text-align: center;
  min-width: 150px;
  line-height: 25px;
}
button.btn {
  background: #2980b9;
  border: 0;
  padding: 7px 20px;
  border-radius: 4px;
  cursor: pointer;
  color: #ecf0f1;
  font-size: medium;
}
.msgList {
  margin-top: 5px;
}
p.user {
  background: #bdc3c7;
  border-radius: 3px;
  margin: 5px 0 !important;
  padding: 5px;
}
p.assistant {
  background: #ecf0f1;
}
tr.nameColum {
  cursor: pointer;
}
input[type="text"] {
  background-image: none;
  padding: 8px;
  margin-right: 5px;
  border-radius: 3px;
}
select {
  height: 30px;
  width: 400px;
}
.flex {
  display: flex;
  justify-content: space-between;
  align-content: center;
}
```

```html
// assigne-form.php (React-PHP-Jquery)
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
```
