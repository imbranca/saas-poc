import { useState } from 'react'
import reactLogo from './assets/react.svg'
import viteLogo from '/vite.svg'
import './App.css'
import { Outlet } from 'react-router-dom'
import AppRoutes from './Routes'

function App() {
  const [count, setCount] = useState(0)

  return (
    <>
      {/* <div>
        App
        <div className="flex">
          <div className="w-1/2">
          dede</div>
          <div className="w-1/2">
          dede</div>
        </div>
      </div> */}
      <AppRoutes></AppRoutes>
    </>
  )
}

export default App
