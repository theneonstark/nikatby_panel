import Layout from '@/components/layout'
import { ServicesContent } from '@/components/services-content'
import { PenLine } from 'lucide-react'
import React from 'react'

export default function services({services}) {
  return (
    <Layout>
        <ServicesContent bbps_service={services}/>
        <div className='flex items-center justify-center gap-2 opacity-70 mt-4'>
          <img src="/company/logo.png" alt="" className='w-20 h-full'/>
          <p className='text-xl text-gray-500 font-extralight'>|</p>
          <img src="/company/bharatconnect.png" alt="" className='w-16 h-full'/>
        </div>
    </Layout>
  )
}