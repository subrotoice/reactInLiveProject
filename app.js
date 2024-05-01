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
