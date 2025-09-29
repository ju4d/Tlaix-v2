#!/usr/bin/env python3
"""
Sistema de predicción en tiempo real para Tlaix
Actualiza automáticamente con nuevos datos de demanda
"""

import argparse
import json
import sys
import os
from datetime import datetime, timedelta
import pandas as pd
import numpy as np
from sklearn.linear_model import LinearRegression
from sklearn.preprocessing import StandardScaler

class TlaixPredictor:
    def __init__(self, data_file=None):
        self.data_file = data_file or self._get_default_file()
        self.scaler = StandardScaler()

    def _get_default_file(self):
        """Obtiene la ruta del archivo de datos"""
        base_path = os.path.dirname(os.path.abspath(__file__))
        return os.path.join(base_path, 'storage', 'app', 'predictions', 'history.csv')

    def load_data(self):
        """Carga datos históricos desde CSV"""
        if not os.path.exists(self.data_file):
            return pd.DataFrame(columns=['date', 'demand'])

        try:
            df = pd.read_csv(self.data_file, parse_dates=['date'])
            df = df.sort_values('date')
            df = df.dropna(subset=['demand'])
            return df
        except Exception as e:
            print(json.dumps({'error': f'Error cargando datos: {str(e)}'}), file=sys.stderr)
            return pd.DataFrame(columns=['date', 'demand'])

    def add_temporal_features(self, df):
        """Añade características temporales para mejorar predicciones"""
        df = df.copy()
        df['day_of_week'] = df['date'].dt.dayofweek
        df['day_of_month'] = df['date'].dt.day
        df['month'] = df['date'].dt.month
        df['is_weekend'] = df['day_of_week'].isin([5, 6]).astype(int)
        df['week_of_year'] = df['date'].dt.isocalendar().week

        # Añadir tendencia temporal
        df['days_since_start'] = (df['date'] - df['date'].min()).dt.days

        return df

    def predict(self, days=7):
        """Genera predicciones para los próximos N días"""
        df = self.load_data()

        if len(df) < 2:
            return self._fallback_prediction(days)

        # Añadir características temporales
        df = self.add_temporal_features(df)

        # Preparar datos para el modelo
        feature_columns = ['days_since_start', 'day_of_week', 'is_weekend', 'day_of_month']
        X = df[feature_columns].values
        y = df['demand'].values

        # Entrenar modelo
        try:
            # Escalar características
            X_scaled = self.scaler.fit_transform(X)

            # Modelo de regresión lineal
            model = LinearRegression()
            model.fit(X_scaled, y)

            # Generar predicciones
            last_date = df['date'].max()
            predictions = []

            for i in range(1, days + 1):
                future_date = last_date + timedelta(days=i)

                # Crear características para fecha futura
                day_of_week = future_date.dayofweek
                is_weekend = 1 if day_of_week in [5, 6] else 0
                day_of_month = future_date.day
                days_since_start = (future_date - df['date'].min()).days

                # Preparar características
                X_future = np.array([[days_since_start, day_of_week, is_weekend, day_of_month]])
                X_future_scaled = self.scaler.transform(X_future)

                # Predecir
                demand_pred = model.predict(X_future_scaled)[0]

                # Ajustes basados en patrones
                demand_pred = self._apply_adjustments(demand_pred, future_date, df)

                predictions.append({
                    'date': future_date.date().isoformat(),
                    'demand': round(float(max(0, demand_pred)), 2),
                    'confidence': self._calculate_confidence(df, i)
                })

            return {
                'predictions': predictions,
                'model_info': {
                    'training_samples': len(df),
                    'last_update': last_date.isoformat(),
                    'features_used': feature_columns,
                    'mean_demand': round(float(df['demand'].mean()), 2),
                    'std_demand': round(float(df['demand'].std()), 2)
                }
            }

        except Exception as e:
            print(json.dumps({'error': f'Error en predicción: {str(e)}'}), file=sys.stderr)
            return self._fallback_prediction(days)

    def _apply_adjustments(self, base_demand, future_date, historical_df):
        """Aplica ajustes basados en patrones históricos"""
        # Factor de fin de semana
        if future_date.dayofweek in [5, 6]:
            base_demand *= 1.3

        # Factor estacional (simulado)
        day_of_year = future_date.timetuple().tm_yday
        seasonal_factor = 1 + (0.2 * np.sin(2 * np.pi * day_of_year / 365))
        base_demand *= seasonal_factor

        # Tendencia basada en últimos 7 días
        if len(historical_df) >= 7:
            recent_avg = historical_df.tail(7)['demand'].mean()
            overall_avg = historical_df['demand'].mean()
            if recent_avg > overall_avg * 1.1:
                base_demand *= 1.05
            elif recent_avg < overall_avg * 0.9:
                base_demand *= 0.95

        return base_demand

    def _calculate_confidence(self, df, days_ahead):
        """Calcula nivel de confianza de la predicción"""
        if len(df) < 7:
            return 'low'

        # Confianza disminuye con días hacia adelante
        if days_ahead <= 3:
            return 'high'
        elif days_ahead <= 7:
            return 'medium'
        else:
            return 'low'

    def _fallback_prediction(self, days):
        """Predicción de respaldo cuando no hay suficientes datos"""
        now = datetime.now().date()
        predictions = []

        for i in range(1, days + 1):
            future_date = now + timedelta(days=i)
            day_of_week = future_date.weekday()

            # Demanda base
            base_demand = 15

            # Mayor demanda en fin de semana
            if day_of_week in [5, 6]:
                base_demand *= 1.4

            # Variación aleatoria pequeña
            variation = np.random.uniform(-2, 2)
            demand = base_demand + variation

            predictions.append({
                'date': future_date.isoformat(),
                'demand': round(float(max(5, demand)), 2),
                'confidence': 'low'
            })

        return {
            'predictions': predictions,
            'model_info': {
                'training_samples': 0,
                'last_update': None,
                'note': 'Predicciones basadas en modelo básico - se necesitan más datos históricos'
            }
        }

    def update_history(self, date, demand):
        """Actualiza el archivo histórico con nueva demanda"""
        try:
            df = self.load_data()

            # Crear nuevo registro
            new_row = pd.DataFrame({
                'date': [pd.to_datetime(date)],
                'demand': [float(demand)]
            })

            # Combinar con datos existentes
            df = pd.concat([df, new_row], ignore_index=True)

            # Eliminar duplicados (mantener el más reciente)
            df = df.drop_duplicates(subset=['date'], keep='last')
            df = df.sort_values('date')

            # Guardar
            os.makedirs(os.path.dirname(self.data_file), exist_ok=True)
            df.to_csv(self.data_file, index=False)

            return {'success': True, 'message': f'Datos actualizados: {date}'}

        except Exception as e:
            return {'success': False, 'error': str(e)}


def main():
    parser = argparse.ArgumentParser(description='Sistema de predicción Tlaix')
    parser.add_argument('--file', default=None, help='Archivo CSV con histórico')
    parser.add_argument('--days', type=int, default=7, help='Días a predecir')
    parser.add_argument('--update', action='store_true', help='Actualizar datos históricos')
    parser.add_argument('--date', help='Fecha para actualizar (YYYY-MM-DD)')
    parser.add_argument('--demand', type=float, help='Demanda para actualizar')

    args = parser.parse_args()

    predictor = TlaixPredictor(args.file)

    if args.update:
        if not args.date or args.demand is None:
            print(json.dumps({'error': 'Se requiere --date y --demand para actualizar'}))
            sys.exit(1)

        result = predictor.update_history(args.date, args.demand)
        print(json.dumps(result))
    else:
        result = predictor.predict(args.days)
        print(json.dumps(result, indent=2))


if __name__ == '__main__':
    main()
