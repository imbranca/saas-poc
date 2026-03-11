import { StrictMode } from 'react'
import { createRoot } from 'react-dom/client'
import './index.css'
import App from './App.tsx'
import { BrowserRouter } from 'react-router-dom'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { CookiesProvider } from 'react-cookie'
import { AuthProvider } from './Context/AuthContext.tsx'
const queryClient = new QueryClient();

createRoot(document.getElementById('root')!).render(
  <StrictMode>
    <BrowserRouter>
      <QueryClientProvider client={queryClient}>
        <CookiesProvider>
          <AuthProvider>
            <App />
          </AuthProvider>
        </CookiesProvider>
      </QueryClientProvider>
    </BrowserRouter>
  </StrictMode>,
)
