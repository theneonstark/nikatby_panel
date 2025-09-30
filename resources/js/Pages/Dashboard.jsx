"use client"

import { motion } from "framer-motion"
import { KPICards } from "@/components/kpi-cards"
import { FinancialChart } from "@/components/financial-chart"
import { SalesDistribution } from "@/components/sales-distribution"
import { TrafficSources } from "@/components/traffic-sources"
import { VisitorStats } from "@/components/visitor-stats"
import Layout from "@/components/layout"
import { Head } from "@inertiajs/react"

const containerVariants = {
  hidden: { opacity: 0 },
  visible: {
    opacity: 1,
    transition: {
      staggerChildren: 0.1,
    },
  },
}

const itemVariants = {
  hidden: { opacity: 0, y: 20 },
  visible: { opacity: 1, y: 0 },
}

export default function Dashboard() {
  return (
    <Layout title="Dashboard">
        <motion.div variants={containerVariants} initial="hidden" animate="visible" className="space-y-6">
      <motion.div variants={itemVariants}>
        <KPICards />
      </motion.div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <motion.div variants={itemVariants}>
          <FinancialChart />
        </motion.div>
        <motion.div variants={itemVariants}>
          <VisitorStats />
        </motion.div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <motion.div variants={itemVariants}>
          <SalesDistribution />
        </motion.div>
        <motion.div variants={itemVariants}>
          <TrafficSources />
        </motion.div>
      </div>
    </motion.div>
    </Layout>
  )
}
