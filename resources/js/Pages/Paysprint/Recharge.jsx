import Layout from '@/components/layout'
import { RechargeContent } from '@/components/recharge-content'
import { getOperators } from '@/lib/apis'
import React, { useEffect, useState } from 'react'

export default function Recharge() {
  const [operators, setOperators] = useState([])

  useEffect(() => {
    const fetchOperator = async () => {
      try {
        const operator = await getOperators()
        console.log("Operators:", operator)
        setOperators(operator)
      } catch (error) {
        console.error("Error fetching operators:", error)
      }
    }

    fetchOperator()
  }, [])

  return (
    <Layout title="Recharge">
      <RechargeContent operators={operators} />
    </Layout>
  )
}
