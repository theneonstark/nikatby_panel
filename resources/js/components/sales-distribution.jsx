"use client"

import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { ChartContainer, ChartTooltip, ChartTooltipContent } from "@/components/ui/chart"
import { PieChart, Pie, Cell, ResponsiveContainer } from "recharts"

const data = [
  { name: "Product A", value: 35, color: "hsl(var(--chart-1))" },
  { name: "Product B", value: 30, color: "hsl(var(--chart-2))" },
  { name: "Product C", value: 35, color: "hsl(var(--chart-3))" },
]

export function SalesDistribution() {
  return (
    <Card>
      <CardHeader>
        <CardTitle>Sales Distribution</CardTitle>
        <CardDescription>Product sales breakdown</CardDescription>
      </CardHeader>
      <CardContent>
        <div className="grid grid-cols-3 gap-4 mb-4">
          {data.map((item, index) => (
            <div key={item.name} className="text-center">
              <div className="text-2xl font-bold text-foreground">{item.value}%</div>
              <div className="text-sm text-muted-foreground">{item.name}</div>
            </div>
          ))}
        </div>
        <ChartContainer
          config={{
            productA: {
              label: "Product A",
              color: "hsl(var(--chart-1))",
            },
            productB: {
              label: "Product B",
              color: "hsl(var(--chart-2))",
            },
            productC: {
              label: "Product C",
              color: "hsl(var(--chart-3))",
            },
          }}
          className="h-[200px]"
        >
          <ResponsiveContainer width="100%" height="100%">
            <PieChart>
              <Pie data={data} cx="50%" cy="50%" innerRadius={60} outerRadius={80} paddingAngle={5} dataKey="value">
                {data.map((entry, index) => (
                  <Cell key={`cell-${index}`} fill={entry.color} />
                ))}
              </Pie>
              <ChartTooltip content={<ChartTooltipContent />} />
            </PieChart>
          </ResponsiveContainer>
        </ChartContainer>
      </CardContent>
    </Card>
  )
}
