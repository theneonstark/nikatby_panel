import Layout from '@/components/layout'
import { ServicesContent } from '@/components/services-content'
import React from 'react'

export default function services({services}) {
  return (
    <Layout>
        <ServicesContent bbps_service={services}/>
    </Layout>
  )
}