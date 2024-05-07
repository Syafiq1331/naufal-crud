// src/ImageGallery.js
import React, { useEffect, useState } from 'react';
import { Link, redirect } from 'react-router-dom';

const IndexProductGallery = () => {
  const [images, setImages] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchImages = async () => {
      try {
        const response = await fetch('http://127.0.0.1:8000/api/products'); // Change to your API endpoint
        if (!response.ok) {
          throw new Error('Failed to fetch images');
        }
        const data = await response.json();
        setImages(data.data); // Assuming data is an array of image objects
        setLoading(false);
      } catch (error: any) {
        setError(error.message);
        setLoading(false);
      }
    };

    fetchImages();
  }, []);

  if (loading) {
    return <div>Loading...</div>;
  }

  if (error) {
    return <div>Error: {error}</div>;
  }

  const handleDelete = async (event: any) => {
    event.preventDefault()
    console.log("DELETE PRODUCT", event.target.value)
    try {
      const call = await fetch("http://127.0.0.1:8000/api/products/" + event.target.value, {
        method: 'DELETE'
      });

      const data = await call.json();
      console.log(data)

    } catch (error) {

    }
    redirect('/products')
  }

  return (
    <div className="image-gallery">
      {images.map((image, index) => (
        <>
          <div className='my-10 max-w-lg p-3 border border-2'>
            <div className='my-3'>
              <h2 className='text-2xl font-semibold'>{image.name}</h2>
              <p>{image.price}</p>
              <p>{image.description} --- <span className='font-medium italic'>it has image gallery total: {image.photos.length} image </span></p>
              <form className='flex gap-3 my-2'>
                <input type="hidden" name="id" value={image.id} />
                <Link className='w-full sm:w-auto py-2 px-3 bg-indigo-600 hover:bg-indigo-700 disabled:bg-indigo-300 dark:disabled:bg-indigo-800 text-white dark:disabled:text-indigo-400 text-sm font-semibold rounded-md shadow focus:outline-none cursor-pointer' to={'/products/' + image.id + '/edit'}>Edit</Link>
                <button value={image.id} onClick={handleDelete} className='w-full sm:w-auto py-2 px-3 bg-indigo-600 hover:bg-indigo-700 disabled:bg-indigo-300 dark:disabled:bg-indigo-800 text-white dark:disabled:text-indigo-400 text-sm font-semibold rounded-md shadow focus:outline-none cursor-pointer'>Delete</button>
              </form>
            </div>

            <img className='max-w-sm' key={index} src={'http://127.0.0.1:8000/storage/' + image.thumbnail} alt={image.name} />
          </div>
        </>
      ))}
    </div>
  );
};

export default IndexProductGallery;
