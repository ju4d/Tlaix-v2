#!/usr/bin/env python3
"""
Simple prediction script for Tlaix.
Usage:
  python3 predict.py --file storage/app/predictions/history.csv --days 7

CSV format expected: date, demand
date as YYYY-MM-DD, demand numeric
Outputs JSON: {"predictions": [{"date":"YYYY-MM-DD","demand":X}, ...]}
"""

import argparse, json, sys, os
import pandas as pd
import numpy as np
from sklearn.linear_model import LinearRegression
from datetime import datetime, timedelta

def main():
    p = argparse.ArgumentParser()
    p.add_argument('--file', required=False, default=None)
    p.add_argument('--days', type=int, default=7)
    args = p.parse_args()

    if args.file and os.path.exists(args.file):
        df = pd.read_csv(args.file, parse_dates=['date'])
        df = df.sort_values('date')
        df = df.dropna(subset=['demand'])
        # convert date to ordinal for regression
        X = df['date'].map(datetime.toordinal).values.reshape(-1,1)
        y = df['demand'].values
        if len(X) < 2:
            # not enough data, fallback to mean
            mean = float(np.mean(y)) if len(y)>0 else 1.0
            base = (df['date'].max() if len(df)>0 else datetime.now()).to_pydatetime()
            preds = []
            for i in range(1,args.days+1):
                d = (base + timedelta(days=i)).date().isoformat()
                preds.append({'date':d,'demand':round(mean,2)})
            print(json.dumps({'predictions':preds}))
            return
        model = LinearRegression()
        model.fit(X,y)
        last_date = df['date'].max()
        preds = []
        for i in range(1,args.days+1):
            future = (last_date + timedelta(days=i))
            xi = np.array([[future.toordinal()]])
            yi = model.predict(xi)[0]
            preds.append({'date':future.date().isoformat(),'demand':round(float(yi),2)})
        print(json.dumps({'predictions':preds}))
    else:
        # fallback: generate simple decreasing sample
        now = datetime.now().date()
        preds = []
        for i in range(1,args.days+1):
            d = now + timedelta(days=i)
            preds.append({'date':d.isoformat(),'demand': round(10 + 2*np.sin(i),2)})
        print(json.dumps({'predictions':preds}))

if __name__ == '__main__':
    main()
