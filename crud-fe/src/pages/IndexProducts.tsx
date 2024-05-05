import React from 'react';
import IndexProductGallery from '../components/IndexProductGallery';
import { Link } from 'react-router-dom';

const IndexProducts = () => {
  return (
    <div className='mx-3'>
      <h1>Products</h1>
      <Link to='/products/create'>Create Products</Link>
      <IndexProductGallery/>
    </div>
  );
};

export default IndexProducts;
