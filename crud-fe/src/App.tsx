import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import IndexProducts from './pages/IndexProducts';
import AddProducts from './pages/AddProducts';
// import AboutPage from './AboutPage';
// import ContactPage from './ContactPage';
// import NavBar from './NavBar'; // Optional: Navigation component

const App: React.FC = () => {
  return (<>
    <Router> {/* Wrap the app in Router */}
      {/* <NavBar /> Optional: Navigation bar for navigation between pages */}
      <Routes> {/* Define your routes */}
        <Route path="/products" element={<IndexProducts />} />
        <Route path="/products/create" element={<AddProducts />} />
        {/* <Route path="/about" element={<AboutPage />} /> About page */}
        {/* <Route path="/contact" element={<ContactPage />} /> Contact page */}
      </Routes>
    </Router>
  </>
  );
};

export default App;
