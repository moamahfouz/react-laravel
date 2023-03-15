import React, { useState, useEffect } from "react";
import { getUser, removeUserSession, getToken } from "./Utils/Common";
import axios from "axios";

function Dashboard(props) {
  const user = getUser();

  // handle click event of logout button
  const handleLogout = () => {
    removeUserSession();
    props.history.push("/login");
  };

  // get products when the component mounts
  useEffect(() => {
    const token = getToken();
    if (!token) {
      return;
    }

    axios
      .get(`http://localhost:8000/api/products`, {
        headers: { Authorization: `Bearer ${token}` },
      })
      .then((response) => {
        console.log(response.data.data);
        setProducts(response.data.data);
      });
  }, []);

  // set products
  const [products, setProducts] = useState([]);

  return (
    <div>
      Welcome {user.name}!<br />
      <br />
      <div className="products">
        <div className="d-flex">
          <h2>List of Products</h2>
          <a href="/add-product" className="btn btn-primary ml-auto">
            Add Product
          </a>
        </div>
        <hr />
        <table className="table table-bordered">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Detail</th>
              <th>Price</th>
              <th>Images</th>
            </tr>
          </thead>
          <tbody>
            {products.map((product) => (
              <tr key={product.id}>
                <td>{product.id}</td>
                <td>{product.name}</td>
                <td>{product.detail}</td>
                <td>{product.price}</td>
                <td className="text-center">
                  {product.attachments.map((image) => (
                    <img key={image.id} src={image.path} width="100" />
                  ))}
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}

export default Dashboard;
