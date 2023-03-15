import React, { useState, useEffect } from "react";
import { getUser, removeUserSession, getToken } from "./Utils/Common";
import axios from "axios";

function addProduct(props) {
  const user = getUser();

  // collect data
  const handleSubmit = (e) => {
    e.preventDefault();
    const data = new FormData(e.target);    
    axios.defaults.headers.common["Authorization"] = `Bearer ${getToken()}`;
    axios
      .post("http://localhost:8000/api/products", data)
      .then(function (response) {        
        props.history.push("/dashboard");    
      })
      .catch(function (error) {
        console.log(error);
      });
  };

  return (
    <div>
      Welcome {user.name}!<br />
      <br />
      <form id="form" onSubmit={handleSubmit}>
        <input type={"hidden"} name={"_token"} value={getToken()} />
        <div className="form-group">
          <label htmlFor="name">Name</label>
          <input
            type="text"
            className="form-control"
            id="name"
            name="name"
            placeholder="Enter name"
          />
        </div>
        <div className="form-group">
          <label htmlFor="detail">Detail</label>
          <input
            type="text"
            className="form-control"
            id="detail"
            name="detail"
            placeholder="Enter detail"
          />
        </div>
        <div className="form-group">
          <label htmlFor="price">Price</label>
          <input
            type="number"
            className="form-control"
            id="price"
            name="price"
            placeholder="Enter price"
          />
        </div>
        <div className="form-group">
          <label htmlFor="image">Images</label>
          <input
            type="file"
            multiple
            className="form-control"
            id="image"
            name="files"
            placeholder="Enter image"
          />
        </div>
        <button
          type="submit"
          className="btn btn-primary"          
        >
          Submit
        </button>
      </form>
    </div>
  );
}

export default addProduct;
